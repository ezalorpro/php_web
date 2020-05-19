<?php

namespace App\Entity;

use App\Repository\ImagePostRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @ORM\Entity(repositoryClass=ImagePostRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class ImagePost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=511)
     */
    private $image_name;

    /**
     * @ORM\ManyToOne(targetEntity=Post::class)
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $usuario;

    /**
     * @ORM\Column(type="string", length=511)
     */
    private $image_directory;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageName(): ?string
    {
        return $this->image_name;
    }

    public function setImageName(string $image_name): self
    {
        $this->image_name = $image_name;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getUsuario(): ?User
    {
        return $this->usuario;
    }

    public function setUsuario(?User $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * @ORM\PreRemove
     */
    public function deleteImagePost()
    {
        $filesystem = new FileSystem();
        $filesystem->remove($this->image_directory.'/'.$this->image_name);
    }

    public function getImageDirectory(): ?string
    {
        return $this->image_directory;
    }

    public function setImageDirectory(string $image_directory): self
    {
        $this->image_directory = $image_directory;

        return $this;
    }
}
