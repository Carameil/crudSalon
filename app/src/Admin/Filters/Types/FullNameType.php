<?php

namespace App\Admin\Filters\Types;

use App\Entity\User\User;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\ComparisonType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FullNameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value', ChildFullNameType::class, [
                'label' => 'Значение'
            ])
            ->add('comparison', HiddenType::class, [
                'data' => ComparisonType::CONTAINS,
            ]);
    }

}