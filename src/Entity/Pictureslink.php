<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PictureslinkRepository")
 */
class Pictureslink
{
    public const PICTURELINKRAND = ['1snow.jpg','2snow.jpg','3snow.jpg',];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private $linkpictures;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Figure", inversedBy="pictureslinks", cascade={"persist"})
     */
    private $figure;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $first_image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $alt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="pictureslinks", cascade={"persist"})
     */
    private $user;

    private $picture;

    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function __toString(): ?string
    {
        return $this->linkpictures;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLinkpictures(): ?string
    {
        return $this->linkpictures;
    }

    public function setLinkpictures(string $linkpictures): self
    {
        $this->linkpictures = $linkpictures;

        return $this;
    }

    public function getFigure(): ?figure
    {
        return $this->figure;
    }

    public function setFigure(?figure $figure): self
    {
        $this->figure = $figure;

        return $this;
    }

    public function getFirstImage(): ?bool
    {
        return $this->first_image;
    }

    public function setFirstImage(?bool $first_image): self
    {
        $this->first_image = $first_image;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param mixed $picture
     */
    public function setPicture($picture): void
    {
        $this->picture = $picture;
    }

}
