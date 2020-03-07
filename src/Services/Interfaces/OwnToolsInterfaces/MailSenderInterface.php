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

namespace App\Services\OwnToolsInterfaces;

interface MailSenderInterface
{
    /**
     * @param $token
     * @param string $emailUser
     */
    public function sendEmailWithToken($token, string $emailUser);
}