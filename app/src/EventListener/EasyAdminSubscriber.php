<?php

namespace App\EventListener;

use App\Entity\Client;
use App\Entity\Employee;
use App\Entity\User\AbstractedUser;
use App\Entity\User\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['setRole'],
        ];
    }

    public function setRole(BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (get_class($entity) === User::class) {
            $entity->addRole(AbstractedUser::ROLE_ADMIN);
        }
        if ($entity instanceof Employee) {
            $entity->addRole(AbstractedUser::ROLE_EMPLOYEE);
        }
        if ($entity instanceof Client) {
            $entity->addRole(AbstractedUser::ROLE_USER);
        }
    }

}