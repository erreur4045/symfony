<?php

namespace App\Services\FormResolvers;

use App\Entity\Comments;
use App\Entity\Figure;
use App\Entity\User;
use App\Form\EditComType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class FormResolverComment extends FormResolver
{
    /** @var EntityManagerInterface  */
    private $manager;
    /** @var FormFactoryInterface  */
    protected $formFactory;
    /** @var Environment  */
    private $environment;

    /**
     * FormResolverComment constructor.
     * @param EntityManagerInterface $manager
     * @param FormFactoryInterface $formFactory
     * @param Environment $environment
     */
    public function __construct(EntityManagerInterface $manager, FormFactoryInterface $formFactory, Environment $environment)
    {
        $this->manager = $manager;
        $this->formFactory = $formFactory;
        $this->environment = $environment;
        parent::__construct($formFactory);
    }

    /**
     * @param FormInterface $form
     * @param Comments $comment
     * @throws Exception
     */
    public function pushComment(FormInterface $form, Comments $comment)
    {
        //todo : DTO comment
        $text = $form->getData()->getText();
        $comment->setText($text);
        $comment->setDateUpdate(new DateTime('now'));
        $this->manager->persist($comment);
        $this->manager->flush();
    }

    /**
     * @param FormInterface $form
     * @param User $user
     * @param Figure $figure
     * @throws Exception
     */
    public function addCom(FormInterface $form, User $user, Figure $figure)
    {
        $comment = $form->getData();
        $comment->setDatecreate(new DateTime());
        $comment->setFigure($figure);
        $comment->setUser($user);
        $this->manager->persist($comment);
        $this->manager->flush();
    }

    /**
     * @param Request $request
     * @param Comments $comment
     * @param string $trickUrl
     * @return RedirectResponse|Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function updateComment(
        Request $request,
        Comments $comment,
        string $trickUrl
    ) {
        $form = $this->getForm($request, EditComType::class);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->pushComment($form, $comment);
            return new RedirectResponse($trickUrl);
        }
        return new Response(
            $this->environment->render(
                'comments/index.html.twig',
                [
                    'form' => $form->createView(),
                    'comment' => $comment->getText()
                ]
            )
        );
    }
}
