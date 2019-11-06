<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FigureRepository")
 */
class Figure
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Figuregroup", inversedBy="figures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idfiguregroup;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Pictureslink", mappedBy="figure")
     */
    private $pictureslinks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Videolink", mappedBy="figure")
     */
    private $videolinks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comments", mappedBy="idfigure", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="figures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datecreate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateupdate;


    public function __construct()
    {
        $this->pictureslinks = new ArrayCollection();
        $this->videolinks = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIdfiguregroup(): ?figuregroup
    {
        return $this->idfiguregroup;
    }

    public function setIdfiguregroup(?figuregroup $idfiguregroup): self
    {
        $this->idfiguregroup = $idfiguregroup;

        return $this;
    }

    /**
     * @return Collection|Pictureslink[]
     */
    public function getPictureslinks(): Collection
    {
        return $this->pictureslinks;
    }

    public function addPictureslink(Pictureslink $pictureslink): self
    {
        if (!$this->pictureslinks->contains($pictureslink)) {
            $this->pictureslinks[] = $pictureslink;
            $pictureslink->setFigure($this);
        }

        return $this;
    }

    public function removePictureslink(Pictureslink $pictureslink): self
    {
        if ($this->pictureslinks->contains($pictureslink)) {
            $this->pictureslinks->removeElement($pictureslink);
            // set the owning side to null (unless already changed)
            if ($pictureslink->getFigure() === $this) {
                $pictureslink->setFigure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Videolink[]
     */
    public function getVideolinks(): Collection
    {
        return $this->videolinks;
    }

    public function addVideolink(Videolink $videolink): self
    {
        if (!$this->videolinks->contains($videolink)) {
            $this->videolinks[] = $videolink;
            $videolink->setFigure($this);
        }

        return $this;
    }

    public function removeVideolink(Videolink $videolink): self
    {
        if ($this->videolinks->contains($videolink)) {
            $this->videolinks->removeElement($videolink);
            // set the owning side to null (unless already changed)
            if ($videolink->getFigure() === $this) {
                $videolink->setFigure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setIdfigure($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getIdfigure() === $this) {
                $comment->setIdfigure(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDatecreate(): ?\DateTimeInterface
    {
        return $this->datecreate;
    }

    public function setDatecreate(\DateTimeInterface $datecreate): self
    {
        $this->datecreate = $datecreate;

        return $this;
    }

    public function getDateupdate(): ?\DateTimeInterface
    {
        return $this->dateupdate;
    }

    public function setDateupdate(?\DateTimeInterface $dateupdate): self
    {
        $this->dateupdate = $dateupdate;

        return $this;
    }

}
