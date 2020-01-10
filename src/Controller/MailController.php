<?php

namespace App\Controller;

use phpDocumentor\Reflection\Types\This;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\Bridge\Google\Smtp\GmailTransport;
use Symfony\Component\Mailer\Mailer;
class MailController extends AbstractController
{
    /** @var MailerInterface */
    private $mailer;
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmailWithToken($token)
    {
        $email = (new TemplatedEmail())
            ->from('hello@example.com')
            ->to('maximethi@hotmail.fr')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Changement de mot de passe')
            ->htmlTemplate('test.html.twig')
            ->context(['token' => $token]);

        /** @var Symfony\Component\Mailer\SentMessage $sentEmail */
        $sentEmail = $this->mailer->send($email);
        // $messageId = $sentEmail->getMessageId();

        // ...
    }
}
