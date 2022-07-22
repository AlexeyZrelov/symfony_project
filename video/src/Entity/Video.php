<?php

namespace App\Entity;

use App\Repository\VideoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
#[ORM\Table(name: 'videos')]
#[Index(columns: ["title"], name: "title_idx")]
class Video
{
    public const videoForNotLoggedIn = 113716040; // vimeo id
    public const videoForNotLoggedInOrNoMembers = 113716040; // vimeo id
//    public const videoForNotLoggedInOrNoMembers = 'https://player.vimeo.com/video/113716040'; // c_110
    public const VimeoPath = 'https://player.vimeo.com/video/';
    public const  perPage = 5; // for pagination
    public const uploadFolder = '/uploads/videos/';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'string', length: 255)]
    private $path;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $duration;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'videos')]
    private $category;

    #[ORM\OneToMany(mappedBy: 'video', targetEntity: Comment::class)]
    private $comments;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'likedVideos')]
    #[ORM\JoinTable(name: 'likes')]
    private $usersThatLike;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'dislikedVideos')]
    #[ORM\JoinTable(name: 'dislikes')]
    private $usersDontLike;

//    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(['message' => 'Please, upload the video as a MP4 file.'])]
    #[Assert\File(mimeTypes: 'video/mp4')]
    private $uploaded_video;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->usersThatLike = new ArrayCollection();
        $this->usersDontLike = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getVimeoId($user=null): ?string
//    public function getVimeoId(): ?string
    {
        if ($user) {

            return $this->path;

        } else {

            return self::VimeoPath.self::videoForNotLoggedIn;

        }

//        return $this->path;

    }


//    public function getVimeoId(): ?string
//    {
//
//        if (strpos($this->path, self::uploadFolder) !== false) {
//
//            return $this->path;
//
//        }
//
//        $array = explode('/', $this->path);
//
//        return end($array);
//
//    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setVideo($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getVideo() === $this) {
                $comment->setVideo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsersThatLike(): Collection
    {
        return $this->usersThatLike;
    }

    public function addUsersThatLike(User $usersThatLike): self
    {
        if (!$this->usersThatLike->contains($usersThatLike)) {
            $this->usersThatLike[] = $usersThatLike;
        }

        return $this;
    }

    public function removeUsersThatLike(User $usersThatLike): self
    {
        $this->usersThatLike->removeElement($usersThatLike);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsersDontLike(): Collection
    {
        return $this->usersDontLike;
    }

    public function addUsersDontLike(User $usersDontLike): self
    {
        if (!$this->usersDontLike->contains($usersDontLike)) {
            $this->usersDontLike[] = $usersDontLike;
        }

        return $this;
    }

    public function removeUsersDontLike(User $usersDontLike): self
    {
        $this->usersDontLike->removeElement($usersDontLike);

        return $this;
    }

    public function getUploadedVideo()
    {
        return $this->uploaded_video;
    }

    public function setUploadedVideo($uploaded_video): self
    {
        $this->uploaded_video = $uploaded_video;

        return $this;
    }
}
