<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : UpdatePictureController.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Medias\Picture;

use App\Entity\Figure;
use App\Entity\Pictureslink;
use App\Form\AddSinglePictureType;
use App\Repository\FigureRepository;
use App\Repository\PictureslinkRepository;
use App\Responder\Interfaces\ResponderInterface;
use App\Services\FormResolvers\FormResolverMedias;
use App\Traits\ViewsTools;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Route("/media/update/picture/{id}", name="update.picture")
 * @IsGranted("ROLE_USER")
 */
class UpdatePicture
{

    use PictureTools, ViewsTools;
    const UPDATE_PICTURE_TWIG = 'media/UpdatePicture.html.twig';
    const TRICK_PATH = 'trick';

    private EntityManagerInterface $manager;
    private TokenStorageInterface $tokenStorage;
    private FormResolverMedias $formResolverMedias;
    private FlashBagInterface $bag;
    private FigureRepository $figureRepo;
    private PictureslinkRepository $pictureRepo;
    private ResponderInterface $responder;

    /**
     * UpdatePicture constructor.
     * @param EntityManagerInterface $manager
     * @param TokenStorageInterface $tokenStorage
     * @param FormResolverMedias $formResolverMedias
     * @param FlashBagInterface $bag
     * @param FigureRepository $figureRepo
     * @param PictureslinkRepository $pictureRepo
     * @param ResponderInterface $responder
     */
    public function __construct(
        EntityManagerInterface $manager,
        TokenStorageInterface $tokenStorage,
        FormResolverMedias $formResolverMedias,
        FlashBagInterface $bag,
        FigureRepository $figureRepo,
        PictureslinkRepository $pictureRepo,
        ResponderInterface $responder
    ) {
        $this->manager = $manager;
        $this->tokenStorage = $tokenStorage;
        $this->formResolverMedias = $formResolverMedias;
        $this->bag = $bag;
        $this->figureRepo = $figureRepo;
        $this->pictureRepo = $pictureRepo;
        $this->responder = $responder;
    }


    public function __invoke(Request $request)
    {
        $idPicture = $request->get('id');

        /** @var Pictureslink $pictureToUpdate */
        $pictureToUpdate = $this->pictureRepo->find($idPicture);
        $trickId = $pictureToUpdate->getFigure();

        /** @var Figure $figure */
        $figure = $this->figureRepo->findOneBy(['id' => $trickId->getId()]);

        $form = $this->formResolverMedias->getForm($request, AddSinglePictureType::class);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->formResolverMedias->updateOnePicture($form, $figure, $pictureToUpdate);
            $this->displayMessage('success', 'La photo a été modifiée');
            $context = ['slug' => $figure->getSlug()];
            return $this->responder->redirect(self::TRICK_PATH, $context);
        }

        $contextView = [
            'form' => $form->createView(),
            'title' => 'Changer une image'
        ];
        return $this->responder->render(self::UPDATE_PICTURE_TWIG, $contextView);
    }
}
