<?php


namespace App\Traits;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

trait ViewsTools
{
    /** @var FlashBagInterface  */
    private $bag;

    public function displayMessage(string $type, string $message): void
    {
        $this->bag->add($type, $message);
    }
}
