<?php


namespace App\Responder;

use App\Responder\Interfaces\ResponderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Responder implements ResponderInterface
{
    private Environment $twig;
    private UrlGeneratorInterface $router;

    /**
     * Responder constructor.
     * @param Environment $twig
     * @param UrlGeneratorInterface $router
     */
    public function __construct(
        Environment $twig,
        UrlGeneratorInterface $router
    ) {
        $this->twig = $twig;
        $this->router = $router;
    }


    public function render(string $name, array $context = []): Response
    {
        try {
            return new Response(
                $this->twig->render($name, $context)
            );
        } catch (LoaderError $e) {
            dd($e);
        } catch (RuntimeError $e) {
            dd($e);
        } catch (SyntaxError $e) {
            dd($e);
        }
    }


    public function redirect(string $name, array $context = []): RedirectResponse
    {
        return new RedirectResponse($this->router->generate($name, $context));
    }
}
