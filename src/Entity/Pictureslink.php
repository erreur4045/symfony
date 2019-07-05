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
     * @ORM\ManyToOne(targetEntity="App\Entity\figure", inversedBy="pictureslinks")
     */
    private $figure;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="pictureslinks")
     */
    private $user;

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

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }
}
