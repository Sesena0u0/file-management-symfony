<?php

namespace App\Entity;

use App\Repository\FolderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FolderRepository::class)]
class Folder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?float $size = null;

    #[ORM\ManyToOne(inversedBy: 'folder')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'folder', targetEntity: File::class, orphanRemoval: true)]
    private Collection $file;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'folder')]
    private ?self $folder_child = null;

    #[ORM\OneToMany(mappedBy: 'folder_child', targetEntity: self::class)]
    private Collection $folder;

    public function __construct() {
        $this->createdAt = new \DateTimeImmutable;
        $this->file = new ArrayCollection();
        $this->folder = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getSize(): ?float
    {
        return $this->size;
    }

    public function setSize(float $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, File>
     */
    public function getFile(): Collection
    {
        return $this->file;
    }

    public function addFile(File $file): static
    {
        if (!$this->file->contains($file)) {
            $this->file->add($file);
            $file->setFolder($this);
        }

        return $this;
    }

    public function removeFile(File $file): static
    {
        if ($this->file->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getFolder() === $this) {
                $file->setFolder(null);
            }
        }

        return $this;
    }

    public function getFolderChild(): ?self
    {
        return $this->folder_child;
    }

    public function setFolderChild(?self $folder_child): static
    {
        $this->folder_child = $folder_child;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getFolder(): Collection
    {
        return $this->folder;
    }

    public function addFolder(self $folder): static
    {
        if (!$this->folder->contains($folder)) {
            $this->folder->add($folder);
            $folder->setFolderChild($this);
        }

        return $this;
    }

    public function removeFolder(self $folder): static
    {
        if ($this->folder->removeElement($folder)) {
            // set the owning side to null (unless already changed)
            if ($folder->getFolderChild() === $this) {
                $folder->setFolderChild(null);
            }
        }

        return $this;
    }
}
