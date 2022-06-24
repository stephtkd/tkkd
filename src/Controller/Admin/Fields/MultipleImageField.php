<?php

namespace App\Controller\Admin\Fields;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class MultipleImageField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $picturesAlbums, ?string $label = null): self
    {
        return (new self())
            ->setProperty($picturesAlbums)
            ->setFormType(FileType::class)
            ->setFormTypeOptions([
                'multiple' => true,
                'data_class' => null,
            ]);
    }
}