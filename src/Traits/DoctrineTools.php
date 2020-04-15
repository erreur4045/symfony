<?php


namespace App\Traits;


use Doctrine\ORM\EntityManagerInterface;

trait DoctrineTools
{
    /** @var EntityManagerInterface  */
    private $manager;

    /**
     * @param object $object
     */
    protected function pushInDataBase($object): void
    {
        $this->manager->persist($object);
        $this->getFlush();
    }

    /**
     * @param object $object
     */
    protected function removeFromDataBase($object): void
    {
        $this->manager->remove($object);
        $this->getFlush();
    }

    protected function getFlush(): void
    {
        $this->manager->flush();
    }
}