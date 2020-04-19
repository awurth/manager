<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class AdminMenuBuilder
{
    private $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function create(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('Project Menu');

        $menu
            ->addChild('Admin', [
                'attributes' => [
                    'class' => 'header'
                ],
                'extras' => [
                    'icon' => 'fas fa-wrench',
                    'translation_domain' => false
                ],
                'label' => 'Administration',
                'route' => 'app_admin'
            ]);

        $menu
            ->addChild('Users', [
                'extras' => [
                    'icon' => 'fas fa-users',
                    'routes' => [
                        'app_admin_user_create'
                    ]
                ],
                'label' => 'admin.users',
                'route' => 'app_admin_users'
            ]);

        return $menu;
    }
}
