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
//todo : sortir de l'abstract controller
class MailController extends AbstractController
{
    /** @var MailerInterface */
    private $mailer;
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    //todo : changer l'email d'envoi
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
         $this->mailer->send($email, null);
        // $messageId = $sentEmail->getMessageId();

        // ...
    }
}
