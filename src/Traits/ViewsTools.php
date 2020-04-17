<?php


namespace App\Traits;

trait ViewsTools
{
    public function displayMessage(string $type, string $message): void
    {
        $this->bag->add($type, $message);
    }
}
