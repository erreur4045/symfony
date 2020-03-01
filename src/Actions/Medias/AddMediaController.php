<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : AddMediaController.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Medias;


use App\Actions\OwnAbstractController;
use App\Entity\Figure;
use App\Form\FigureAddMediaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddMediaController extends OwnAbstractController
{
    /**
     * @Route("/edit/medias/{slug}", name="add.medias")
     * @IsGranted("ROLE_USER")
     */
    public function editMedias(Request $request)
    {
        /** @var Figure $figure */
        $figure = $this->manager->getRepository(Figure::class)->findOneBy(['slug' => $request->query->get('slug')]);

        $form = $this->formResolverMedias->getForm($request, FigureAddMediaType::class);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->formResolverMedias->updateMedias($form, $figure);
                $this->bag->add('success', 'Les medias ont été ajouter');
                return new RedirectResponse($this->router->generate('trick', ['slug' => $figure->getSlug()]));
            }
        return new Response(
            $this->environment->render(
                'media/UpdateMedias.html.twig',
                [
                    'form' => $form->createView(),
                    'title' => 'Changer une image'
                ]
            )
        );
    }
}