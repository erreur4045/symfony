<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : EditCom.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Comments;

use App\Entity\Comments;
use App\Entity\Figure;
use App\Services\FormResolvers\FormResolverComment;
use App\Traits\ViewsToolsTrait;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * @Route("/editcom/{id}", name="edit.comment")
 * @IsGranted("ROLE_USER")
*/
class EditComment
{
    use CommentsToolsTrait, ViewsToolsTrait;

    /** @var FormResolverComment */
    private $resolver;

    /**
     * EditCom constructor.
     * @param FormResolverComment $formResolverComment
     */
    public function __construct(FormResolverComment $formResolverComment)
    {
        $this->resolver = $formResolverComment;
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function __invoke(Request $request)
    {
        /** @var Comments $comment */
        $comment = $this->getComment($request);
        /** @var Figure $tricks */
        $tricks = $this->getTrick($request);
        /** @var string $trickUrl */
        $trickUrl = $this->getTrickUrl($tricks);
        if (!$this->isConnectedUserConsistentWithCommentUser($comment)) {
            $this->displayMessage('warning', 'Vous ne pouvez pas modifier ce commentaire');
        } else {
            return $this->resolver->updateComment($request, $comment, $trickUrl);
        }
        return new RedirectResponse($trickUrl);
    }
}
