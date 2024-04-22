<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'post', orphanRemoval: true)]
    private Collection $comments;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'post')]
    private Collection $images;



    #[ORM\OneToMany(targetEntity: ReplyComment::class, mappedBy: 'post', orphanRemoval: true)]
    private Collection $replyComments;



    #[ORM\ManyToOne(targetEntity: self::class)]
    #[ORM\JoinColumn(name: 'retweet_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    private ?self $retweet = null;




    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->replyComments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setPost($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getPost() === $this) {
                $image->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Retweet>
     */
    public function getRetweets(): Collection
    {
        return $this->retweets;
    }

    public function addRetweet(Retweet $retweet): static
    {
        if (!$this->retweets->contains($retweet)) {
            $this->retweets->add($retweet);
            $retweet->setPost($this);
        }

        return $this;
    }

    public function removeRetweet(Retweet $retweet): static
    {
        if ($this->retweets->removeElement($retweet)) {
            // set the owning side to null (unless already changed)
            if ($retweet->getPost() === $this) {
                $retweet->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ReplyComment>
     */
    public function getReplyComments(): Collection
    {
        return $this->replyComments;
    }

    public function addReplyComment(ReplyComment $replyComment): static
    {
        if (!$this->replyComments->contains($replyComment)) {
            $this->replyComments->add($replyComment);
            $replyComment->setPost($this);
        }

        return $this;
    }

    public function removeReplyComment(ReplyComment $replyComment): static
    {
        if ($this->replyComments->removeElement($replyComment)) {
            // set the owning side to null (unless already changed)
            if ($replyComment->getPost() === $this) {
                $replyComment->setPost(null);
            }
        }

        return $this;
    }

    public function getRetweetContent(): ?string
    {
        return $this->retweetContent;
    }

    public function setRetweetContent(?string $retweetContent): static
    {
        $this->retweetContent = $retweetContent;

        return $this;
    }

    public function getRetweet(): ?self
    {
        return $this->retweet;
    }

    public function setRetweet(?self $retweet): static
    {
        $this->retweet = $retweet;

        return $this;
    }


}