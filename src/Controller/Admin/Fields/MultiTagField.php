<?php


namespace App\Controller\Admin\Fields;

use App\Form\MultiTagType;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


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
                'expanded' => true,
            ])
           // ->setColumns('col-6')
        ;
    }



}