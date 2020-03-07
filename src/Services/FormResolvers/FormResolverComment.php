<?php

namespace App\Services\FormResolvers;

use App\Entity\Comments;
use App\Entity\Figure;
use App\Entity\User;
use App\Services\Interfaces\FormResolversInterfaces\FormResolverCommentInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FormResolverComment extends FormResolver implements FormResolverCommentInterface
{
    /** @var EntityManagerInterface  */
    private $manager;
    /** @var FormFactoryInterface  */
    protected $formFactory;

    /**
     * FormResolverComment constructor.
     * @param EntityManagerInterface $manager
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(EntityManagerInterface $manager, FormFactoryInterface $formFactory)
    {
        $this->manager = $manager;
        $this->formFactory = $formFactory;
        parent::__construct($formFactory);
    }


    /**
     * @param FormInterface $form
     * @param Comments $comment
     * @throws \Exception
     */
    public function updateCom(FormInterface $form, Comments $comment)
    {
        $comment->setText($form->getData()->getText())
            ->setDateupdate(new \DateTime('now'));
        $this->manager->persist($comment);
        $this->manager->flush();
    }

    /**
     * @param FormInterface $form
     * @param User $user
     * @param Figure $figure
     * @throws \Exception
     */
    public function addCom(FormInterface $form, User $user, Figure $figure)
    {
        $comment = $form->getData();
        $comment->setDatecreate(new \DateTime())->setIdfigure($figure)->setUser($user);
        $this->manager->persist($comment);
        $this->manager->flush();
    }
}
