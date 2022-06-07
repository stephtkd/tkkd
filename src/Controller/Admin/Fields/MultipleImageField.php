<?php

namespace App\Controller\Admin\Fields;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class MultipleImageField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setFormType(FileType::class)
            ->setFormTypeOptions([
                'multiple' => true,
                'data_class' => null,
                /*'constraints' => [
                    new File([
                        'maxSize' => '1024k'
                    ])
                ]*/
            ]);
    }
}