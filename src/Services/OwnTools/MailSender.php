<?php

namespace App\Services\OwnTools;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\SentMessage;

class MailSender
{
    /** @var MailerInterface  */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmailWithToken($token, string $emailUser)
    {
        $email = (new TemplatedEmail())
            ->from('hello@example.com')
            ->to($emailUser)
            ->subject('Changement de mot de passe')
            ->htmlTemplate('emails/mailTempleteForPasswordRecovery.html.twig')
            ->context(['token' => $token]);

        /** @var SentMessage $sentEmail */
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }
    }
}
