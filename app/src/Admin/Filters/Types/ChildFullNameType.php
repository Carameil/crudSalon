<?php

namespace App\Admin\Filters\Types;

use EasyCorp\Bundle\EasyAdminBundle\Form\Type\ComparisonType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChildFullNameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'help' => 'firstName',
            ])
            ->add('lastName', TextType::class, [
                'help' => 'lastName',
            ])
            ->add('middleName', TextType::class, [
                'help' => 'middleName',
            ])
            ->add('comparison', HiddenType::class, [
                'data' => ComparisonType::CONTAINS,
            ])
        ;
    }
}