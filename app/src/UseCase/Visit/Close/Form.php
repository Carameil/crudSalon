<?php

namespace App\UseCase\Visit\Close;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Symfony\Component\Translation\t;

class Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('materials', CollectionType::class, [
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
                'entry_type' => MaterialType::class,
                'prototype' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Command::class,
            'csrf_token_id' => 'csrf_token'
        ]);
    }
}