<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
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
     * @ORM\Column(type="string", length=255)
     */
    private $surname;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datesub;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="smallint")
     */
    private $grade;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mail;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Pictureslink", mappedBy="user")
     */
    private $pictureslinks;

    public function __construct()
    {
        $this->pictureslinks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIduser(): ?int
    {
        return $this->iduser;
    }

    public function setIduser(int $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getDatesub(): ?\DateTimeInterface
    {
        return $this->datesub;
    }

    public function setDatesub(\DateTimeInterface $datesub): self
    {
        $this->datesub = $datesub;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getGrade(): ?int
    {
        return $this->grade;
    }

    public function setGrade(int $grade): self
    {
        $this->grade = $grade;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

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
            $pictureslink->setUser($this);
        }

        return $this;
    }

    public function removePictureslink(Pictureslink $pictureslink): self
    {
        if ($this->pictureslinks->contains($pictureslink)) {
            $this->pictureslinks->removeElement($pictureslink);
            // set the owning side to null (unless already changed)
            if ($pictureslink->getUser() === $this) {
                $pictureslink->setUser(null);
            }
        }

        return $this;
    }
}
