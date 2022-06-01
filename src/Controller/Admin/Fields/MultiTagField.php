<?php


namespace App\Controller\Admin\Fields;

use Doctrine\Common\Collections\ArrayCollection;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MultiTagField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setFormType(ChoiceType::class)
            ->setFormTypeOptions([
                'multiple' => true,

            ]);
    }

}