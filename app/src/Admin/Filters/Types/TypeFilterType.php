<?php

namespace App\Admin\Filters\Types;

use App\Entity\Client;
use App\Entity\Employee;
use App\Entity\User\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeFilterType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => [
                'Клиент' => Client::class,
                'Сотрудник' => Employee::class,
                'Иной' => User::class
            ],
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}