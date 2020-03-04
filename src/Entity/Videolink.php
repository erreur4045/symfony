<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VideolinkRepository")
 */
class Videolink
{
    public const PATTERNYT = '/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((?:\w|-){11})(?:&list=(\S+))?$/';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private $linkvideo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Figure", inversedBy="videolinks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $figure;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLinkvideo(): ?string
    {
        return $this->linkvideo;
    }

    public function setLinkvideo(string $linkvideo): self
    {
        $this->linkvideo = $linkvideo;

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
}
