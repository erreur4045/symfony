<?php

namespace App\Services\OwnTools;


use App\Services\OwnToolsInterfaces\MailSenderInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\SentMessage;

class MailSender implements MailSenderInterface
{
    /** @var MailerInterface  */
    private $mailer;

    /**
     * MailSender constructor.
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param $token
     * @param string $emailUser
     */
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
