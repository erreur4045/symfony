<?php


namespace App\Services;


use App\Controller\MailController;
use App\Entity\Comments;
use App\Entity\Figure;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FormResolverComment extends FormResolver
{
    /** @var EntityManagerInterface */
    private $manager;

    /** @var FlashBagInterface */
    private $bag;

    /** @var  UrlGeneratorInterface */
    private $router;

    public function __construct(
        FormFactoryInterface $formFactory,
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        UrlGeneratorInterface $router
    )
    {
        parent::__construct($formFactory);
        $this->manager = $manager;
        $this->bag = $bag;
        $this->router = $router;
    }

    public function updateCom(FormInterface $form, Comments $comment)
    {
        $comment->setText($form->getData()->getText())
            ->setDateupdate(new \DateTime('now'));
        $this->manager->persist($comment);
        $this->manager->flush();
    }

    public function addCom(FormInterface $form, User $user, Figure $figure)
    {
        $comment = $form->getData();
        $comment->setDatecreate(new \DateTime())->setIdfigure($figure)->setUser($user);
        $this->manager->persist($comment);
        $this->manager->flush();
    }
}