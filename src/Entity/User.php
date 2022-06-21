<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Length(
     *     min = 3,
     *     max = 50,
     *     minMessage = "Username must be greater than or equal {{ limit }} character!",
     *     maxMessage = "Username must be less than or equal {{ limit }} character!"
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=24)
     * @Assert\Length (
     *     min = 3,
     *     max = 24,
     *     minMessage = "Fullname must be greater than or equal {{ limit }} character!",
     *     maxMessage = "Fullname must be less than or equal {{ limit }} character!"
     * )
     */
    private $fullname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $login_status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Email(
     *     message = "The email {{ value }} is not a valid email!"
     * )
     */
    private $email;

    
    /**
     * @ORM\Column(type="string", length=11, nullable=true)
     * @Assert\Length (
     *     max = 11,
     *     maxMessage = "Phone number must be less than or equal {{ limit }} number"
     * )
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $new_password;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="user")
     */
    private $user_post;

    /**
     * @ORM\OneToMany(targetEntity=Reaction::class, mappedBy="user")
     */
    private $user_reaction;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="user")
     */
    private $user_comment;

    /**
     * @ORM\OneToMany(targetEntity=Relationship::class, mappedBy="user")
     */
    private $user_relationship;

    /**
     * @ORM\OneToMany(targetEntity=Relationship::class, mappedBy="friend")
     */
    private $friend_relationship;

    /**
     * @ORM\OneToMany(targetEntity=Notification::class, mappedBy="sender")
     */
    private $user_sender;

    /**
     * @ORM\OneToMany(targetEntity=Notification::class, mappedBy="receiver")
     */
    private $user_receiver;

    public function __construct()
    {
        $this->user_post = new ArrayCollection();
        $this->user_reaction = new ArrayCollection();
        $this->user_comment = new ArrayCollection();
        $this->user_relationship = new ArrayCollection();
        $this->friend_relationship = new ArrayCollection();
        $this->user_sender = new ArrayCollection();
        $this->user_receiver = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getLoginStatus(): ?string
    {
        return $this->login_status;
    }

    public function setLoginStatus(?string $login_status): self
    {
        $this->login_status = $login_status;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }


    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }


    public function getNewPassword(): ?string
    {
        return $this->new_password;
    }

    public function setNewPassword(?string $new_password): self
    {
        $this->new_password = $new_password;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getUserPost(): Collection
    {
        return $this->user_post;
    }

    public function addUserPost(Post $userPost): self
    {
        if (!$this->user_post->contains($userPost)) {
            $this->user_post[] = $userPost;
            $userPost->setUser($this);
        }

        return $this;
    }

    public function removeUserPost(Post $userPost): self
    {
        if ($this->user_post->removeElement($userPost)) {
            // set the owning side to null (unless already changed)
            if ($userPost->getUser() === $this) {
                $userPost->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reaction>
     */
    public function getUserReaction(): Collection
    {
        return $this->user_reaction;
    }

    public function addUserReaction(Reaction $userReaction): self
    {
        if (!$this->user_reaction->contains($userReaction)) {
            $this->user_reaction[] = $userReaction;
            $userReaction->setUser($this);
        }

        return $this;
    }

    public function removeUserReaction(Reaction $userReaction): self
    {
        if ($this->user_reaction->removeElement($userReaction)) {
            // set the owning side to null (unless already changed)
            if ($userReaction->getUser() === $this) {
                $userReaction->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getUserComment(): Collection
    {
        return $this->user_comment;
    }

    public function addUserComment(Comment $userComment): self
    {
        if (!$this->user_comment->contains($userComment)) {
            $this->user_comment[] = $userComment;
            $userComment->setUser($this);
        }

        return $this;
    }

    public function removeUserComment(Comment $userComment): self
    {
        if ($this->user_comment->removeElement($userComment)) {
            // set the owning side to null (unless already changed)
            if ($userComment->getUser() === $this) {
                $userComment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Relationship>
     */
    public function getUserRelationship(): Collection
    {
        return $this->user_relationship;
    }

    public function addUserRelationship(Relationship $userRelationship): self
    {
        if (!$this->user_relationship->contains($userRelationship)) {
            $this->user_relationship[] = $userRelationship;
            $userRelationship->setUser($this);
        }

        return $this;
    }

    public function removeUserRelationship(Relationship $userRelationship): self
    {
        if ($this->user_relationship->removeElement($userRelationship)) {
            // set the owning side to null (unless already changed)
            if ($userRelationship->getUser() === $this) {
                $userRelationship->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Relationship>
     */
    public function getFriendRelationship(): Collection
    {
        return $this->friend_relationship;
    }

    public function addFriendRelationship(Relationship $friendRelationship): self
    {
        if (!$this->friend_relationship->contains($friendRelationship)) {
            $this->friend_relationship[] = $friendRelationship;
            $friendRelationship->setFriend($this);
        }

        return $this;
    }

    public function removeFriendRelationship(Relationship $friendRelationship): self
    {
        if ($this->friend_relationship->removeElement($friendRelationship)) {
            // set the owning side to null (unless already changed)
            if ($friendRelationship->getFriend() === $this) {
                $friendRelationship->setFriend(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getUserSender(): Collection
    {
        return $this->user_sender;
    }

    public function addUserSender(Notification $userSender): self
    {
        if (!$this->user_sender->contains($userSender)) {
            $this->user_sender[] = $userSender;
            $userSender->setSender($this);
        }

        return $this;
    }

    public function removeUserSender(Notification $userSender): self
    {
        if ($this->user_sender->removeElement($userSender)) {
            // set the owning side to null (unless already changed)
            if ($userSender->getSender() === $this) {
                $userSender->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getUserReceiver(): Collection
    {
        return $this->user_receiver;
    }

    public function addUserReceiver(Notification $userReceiver): self
    {
        if (!$this->user_receiver->contains($userReceiver)) {
            $this->user_receiver[] = $userReceiver;
            $userReceiver->setReceiver($this);
        }

        return $this;
    }

    public function removeUserReceiver(Notification $userReceiver): self
    {
        if ($this->user_receiver->removeElement($userReceiver)) {
            // set the owning side to null (unless already changed)
            if ($userReceiver->getReceiver() === $this) {
                $userReceiver->setReceiver(null);
            }
        }

        return $this;
    }
}
