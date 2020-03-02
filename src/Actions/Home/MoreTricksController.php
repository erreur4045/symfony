<?php

namespace App\Actions\Home;

use App\Actions\OwnAbstractController;
use App\Entity\Figure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class MoreTricksController extends OwnAbstractController
{
    /**
     * @Route("/more_tricks", name="more.tricks")
     */
    public function loadTricks(Request $request)
    {
        $pageId = $request->attributes->get('page');
        $offset = $pageId * Figure::LIMIT_PER_PAGE - Figure::LIMIT_PER_PAGE ;
        $nb_tricks = $this->manager->getRepository(Figure::class)->count([]);
        $rest = $pageId * Figure::LIMIT_PER_PAGE < $nb_tricks ? true : false;

        /** @var Figure $tricksToShow */
        $tricksToShow = $this->manager->getRepository(Figure::class)->findBy([], [], Figure::LIMIT_PER_PAGE, $offset);

        return new Response(
            $this->environment->render(
                'block_for_include/block_for_tricks_ajax.html.twig',
                [
                'tricksToShow' => $tricksToShow,
                'rest' => $rest
                ]
            )
        );
    }
}
