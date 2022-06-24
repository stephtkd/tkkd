<?php


namespace App\Controller\Admin\Fields;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MultiTagField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $Tag, ?string $label = null): self
    {
        return (new self())
            ->setProperty($Tag)
            ->setFormType(ChoiceType::class)
            ->setFormTypeOptions([
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }
}