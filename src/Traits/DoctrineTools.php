<?php


namespace App\Traits;

trait DoctrineTools
{
    /**
     * @param object $object
     */
    protected function pushInDataBase($object): void
    {
        $this->manager->persist($object);
        $this->manager->flush();
    }

    /**
     * @param object $object
     */
    protected function removeFromDataBase($object): void
    {
        $this->manager->remove($object);
        $this->manager->flush();
    }
}
