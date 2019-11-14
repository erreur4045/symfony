<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PictureslinkRepository")
 */
class Pictureslink
{
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Figure", inversedBy="pictureslinks")
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

}
