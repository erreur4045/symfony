<?php

namespace App\Actions\Comments;

use App\Actions\OwnAbstractController;
use App\Entity\Comments;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoreCom extends OwnAbstractController
{
    /**
     * @Route("tricks/details/more_com", name="more.coms")
     */
    public function loadTricks(Request $request)
    {
        $pageId = $request->query->get('page');
        $figureId = $request->query->get('figureid');
        $offset = $pageId * Comments::LIMIT_PER_PAGE - Comments::LIMIT_PER_PAGE;
        $nb_coms = $this->manager->getRepository(Comments::class)->findBy(['idfigure' => $figureId]);
        if ($nb_coms > Comments::LIMIT_PER_PAGE) {
            $rest = false;
        } else {
            $rest = $pageId * Comments::LIMIT_PER_PAGE < $nb_coms;
        }

        /** @var Comments $comsToShow */
        $comsToShow = $this->manager->getRepository(Comments::class)
            ->findBy(['idfigure' => $figureId], [], Comments::LIMIT_PER_PAGE, $offset);

        return new Response(
            $this->environment->render(
                'block_for_include/block_for_coms_ajax.html.twig',
                [
                'user' => $this->tokenStorage->getToken()->getUser(),
                'comsToShow' => $comsToShow,
                'rest' => $rest
                ]
            )
        );
    }
}
