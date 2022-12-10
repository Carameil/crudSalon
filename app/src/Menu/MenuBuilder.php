<?php

declare(strict_types=1);

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MenuBuilder
{
    /** @var FactoryInterface */
    private FactoryInterface $factory;

    /** @var  AuthorizationCheckerInterface */
    private AuthorizationCheckerInterface $authChecker;

    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $authorizationCheckerInterface)
    {
        $this->factory = $factory;
        $this->authChecker = $authorizationCheckerInterface;
    }

    public function createMainMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
        $menu = $this->addItems($menu);
        $menu = $this->setAttributes($menu);

        return $menu;
    }

    private function addItems($menu)
    {
        $menu->addChild('Dashboard', ['route' => 'app_home'])
            ->setExtra('icon', 'nav-icon icon-speedometer')
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');
        if ($this->authChecker->isGranted('ROLE_ADMIN') !== false) {
            $menu->addChild('Admin', ['route' => 'admin']);
        }

        return $menu;
    }

    private function setAttributes($menu)
    {
        foreach ($menu as $item) {
            $item->setLinkAttribute('class', 'nav-link'); // a class
        }
        $menu->setChildrenAttribute('class', 'nav nav-pills'); // ul class

        return $menu;
    }
}
