<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    /**
     * @ORM\OneToMany(targetEntity=AlbumPicture::class, mappedBy="Tag", cascade={"persist"})
     */
    private $albumPictures;

    public function __construct()
    {
        $this->albumPictures = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return Collection<int, AlbumPicture>
     */
    public function getAlbumPictures(): Collection
    {
        return $this->albumPictures;
    }

    public function addAlbumPicture(AlbumPicture $albumPicture): self
    {
        if (!$this->albumPictures->contains($albumPicture)) {
            $this->albumPictures[] = $albumPicture;
            $albumPicture->setTag($this);
        }

        return $this;
    }

    public function removeAlbumPicture(AlbumPicture $albumPicture): self
    {
        if ($this->albumPictures->removeElement($albumPicture)) {
            // set the owning side to null (unless already changed)
            if ($albumPicture->getTag() === $this) {
                $albumPicture->setTag(null);
            }
        }

        return $this;
    }

}
