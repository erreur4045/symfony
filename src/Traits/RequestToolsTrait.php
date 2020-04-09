<?php


namespace App\Traits;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

trait RequestToolsTrait
{
    /** @var TokenStorageInterface  */
    private $tokenStorage;

    /**
     * RequestToolsTrait constructor.
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return string|UserInterface
     */
    public function getUserFromToken()
    {
        return $this->tokenStorage->getToken()->getUser();
    }
}
