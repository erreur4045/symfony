<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 07 03 2020
 * Project : symfonytestversion
 * File : MailSenderInterface.php
 * PHP Version : 7.3.5
 */

namespace App\Services\Interfaces\OwnTools;

use Symfony\Component\Mailer\MailerInterface;

interface MailSenderInterface
{
    /**
     * MailSender constructor.
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer);

    /**
     * @param $token
     * @param string $emailUser
     */
    public function sendEmailWithToken(
        $token,
        string $emailUser
    );
}
