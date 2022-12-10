<?php

namespace App\ReadModel\Visit\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'ФИО',
                'onchange' => 'this.form.submit()',
            ]])
            ->add('service', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'Имя услуги',
                'onchange' => 'this.form.submit()',
            ]])
            ->add('dateTime', Type\DateTimeType::class, ['required' => false, 'attr' => [
                'placeholder' => 'Дата',
                'timezone' => false,
                'input_format' => 'Y-m-d H:i:s',
                'input' => 'datetime_immutable',
                'onchange' => 'this.form.submit()',
            ]]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Filter::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}
