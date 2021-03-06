<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PictureslinkRepository")
 */

//todo : change class name to Picture
class Pictureslink
{
    public const PICTURELINKTRICKRAND = ['1snow','2snow','3snow',];
    public const PICTURELINKUSERRAND = 'User.png';
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

    /**
     * @return mixed
     */
    public function getPicture()
    {
        return $this->picture;
    }
}
