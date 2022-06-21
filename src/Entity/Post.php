<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $caption;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_like;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_comment;

    /**
     * @ORM\Column(type="datetime")
     */
    private $upload_time;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="user_post")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Reaction::class, mappedBy="post")
     */
    private $Post_Reaction;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="post")
     */
    private $Comment_Post;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $deleted;

    public function __construct()
    {
        $this->Post_Reaction = new ArrayCollection();
        $this->Comment_Post = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(?string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getTotalLike(): ?int
    {
        return $this->total_like;
    }

    public function setTotalLike(?int $total_like): self
    {
        $this->total_like = $total_like;

        return $this;
    }

    public function getTotalComment(): ?int
    {
        return $this->total_comment;
    }

    public function setTotalComment(?int $total_comment): self
    {
        $this->total_comment = $total_comment;

        return $this;
    }

    public function getUploadTime(): ?\DateTimeInterface
    {
        return $this->upload_time;
    }

    public function setUploadTime(\DateTimeInterface $upload_time): self
    {
        $this->upload_time = $upload_time;

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

    /**
     * @return Collection<int, Reaction>
     */
    public function getPostReaction(): Collection
    {
        return $this->Post_Reaction;
    }

    public function addPostReaction(Reaction $postReaction): self
    {
        if (!$this->Post_Reaction->contains($postReaction)) {
            $this->Post_Reaction[] = $postReaction;
            $postReaction->setPost($this);
        }

        return $this;
    }

    public function removePostReaction(Reaction $postReaction): self
    {
        if ($this->Post_Reaction->removeElement($postReaction)) {
            // set the owning side to null (unless already changed)
            if ($postReaction->getPost() === $this) {
                $postReaction->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getCommentPost(): Collection
    {
        return $this->Comment_Post;
    }

    public function addCommentPost(Comment $commentPost): self
    {
        if (!$this->Comment_Post->contains($commentPost)) {
            $this->Comment_Post[] = $commentPost;
            $commentPost->setPost($this);
        }

        return $this;
    }

    public function removeCommentPost(Comment $commentPost): self
    {
        if ($this->Comment_Post->removeElement($commentPost)) {
            // set the owning side to null (unless already changed)
            if ($commentPost->getPost() === $this) {
                $commentPost->setPost(null);
            }
        }

        return $this;
    }


    public function getDeleted(): ?string
    {
        return $this->deleted;
    }

    public function setDeleted(string $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }
}
