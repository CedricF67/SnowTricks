<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrickRepository")
 * @UniqueEntity("name")
 */
class Trick
{
    const PICTURE_PATH = 'uploads/pictures/';
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TrickGroup", inversedBy="trick")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid
     */
    private $trickGroup;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TrickVideo", mappedBy="trick", orphanRemoval=true, cascade={"persist"})
     */
    private $videos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TrickPicture", mappedBy="trick", orphanRemoval=true, cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $pictures;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="trick", orphanRemoval=true)
     */
    private $comments;

    public function __construct()
    {
        $this->createdDate = new \Datetime();
        $this->videos = new ArrayCollection();
        $this->pictures = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getFirstPicture()
    {
        // Attribution d'une image par défaut si la figure n'a aucune immage associée
        if ($this->pictures->count()=== 0) {
            return $this->getPicturePath() . 'default.png';
        }

        return $this->getPicturePath() . $this->pictures->first()->getFile();
    }

    public function getPicturePath()
    {
        return self::PICTURE_PATH;
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

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTrickGroup(): ?TrickGroup
    {
        return $this->trickGroup;
    }

    public function setTrickGroup(?TrickGroup $trickGroup): self
    {
        $this->trickGroup = $trickGroup;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * @return Collection|TrickVideo[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(TrickVideo $video): self
    {
        if(!true === is_null($video->getUrl())) {           
            if (!$this->videos->contains($video)) {
                $this->videos->add($video);
                $video->setTrick($this);
            }
        }

        return $this;
    }

    public function removeVideo(TrickVideo $video): self
    {
        if ($this->videos->contains($video)) {
            $this->videos->removeElement($video);
            // set the owning side to null (unless already changed)
            if ($video->getTrick() === $this) {
                $video->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TrickPicture[]
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(TrickPicture $picture): self
    {  
        if (!$this->pictures->contains($picture)) {
            $this->pictures->add($picture);
            $picture->setTrick($this);
        }

        return $this;
    }

    public function removePicture(TrickPicture $picture): self
    {
        if ($this->pictures->contains($picture)) {
            $this->pictures->removeElement($picture);
            // set the owning side to null (unless already changed)
            if ($picture->getTrick() === $this) {
                $picture->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setTrick($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getTrick() === $this) {
                $comment->setTrick(null);
            }
        }

        return $this;
    }
}
