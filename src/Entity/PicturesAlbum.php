<?php

namespace App\Entity;

use App\Repository\PicturesAlbumRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=PicturesAlbumRepository::class)
 * @Vich\Uploadable
 */
class PicturesAlbum
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
    private $images;

    /**
     * @Vich\UploadableField(mapping="album_directory", fileNameProperty="images")
     * @var File
     */
    private $imagesFile;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=AlbumPicture::class, inversedBy="picturesAlbums")
     */
    private $AlbumPicture;

    public function __construct()
    {
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImages(): ?string
    {
        return $this->images;
    }

    public function setImages(?string $images): self
    {
        $this->images = $images;

        return $this;
    }

    /**
     * @param mixed $imagesFile
     * @throws \Exception
     */

    public function setImagesFile($imagesFile)
    {
        $this->imagesFile = $imagesFile;
        if ($imagesFile) {
            $this->updatedAt = new \DateTime();
        }
    }

    /**
     * @return mixed
     */

    public function getImagesFile()
    {
        return $this->imagesFile;
    }

    public function getAlbumPicture(): ?AlbumPicture
    {
        return $this->AlbumPicture;
    }

    public function setAlbumPicture(?AlbumPicture $AlbumPictures): self
    {
        $this->AlbumPicture = $AlbumPictures;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): ?self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function upload(UploadedFile $file)
    {
        if(null === $file){
            return;
        }

        $file->move(
            './upload/album',
            $file->getClientOriginalName()
        );

        $this->setImages($file->getClientOriginalName());

        $this->setImagesFile(null);
    }
}
