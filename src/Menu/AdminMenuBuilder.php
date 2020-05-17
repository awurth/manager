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
        $menu = $this->factory->createItem('Admin Menu');

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
            ->addChild('Project Groups', [
                'extras' => [
                    'icon' => 'fas fa-object-group'
                ],
                'label' => 'admin.project_groups',
                'route' => 'app_admin_project_group_list'
            ]);

        $menu
            ->addChild('Projects', [
                'extras' => [
                    'icon' => 'fas fa-code'
                ],
                'label' => 'admin.projects',
                'route' => 'app_admin_project_list'
            ]);

        $menu['Projects']->addChild('Projects', [
            'label' => 'admin.projects',
            'route' => 'app_admin_project_list'
        ]);

        $menu['Projects']->addChild('Project Types', [
            'extras' => [
                'routes' => [
                    'app_admin_project_type_create'
                ]
            ],
            'label' => 'admin.project_types',
            'route' => 'app_admin_project_type_list'
        ]);

        $menu
            ->addChild('Servers', [
                'extras' => [
                    'icon' => 'fas fa-server'
                ],
                'label' => 'admin.servers',
                'route' => 'app_admin_server_list'
            ]);

        $menu
            ->addChild('Credentials', [
                'extras' => [
                    'icon' => 'fas fa-key'
                ],
                'label' => 'admin.credentials',
                'route' => 'app_admin_credentials_list'
            ]);

        $menu
            ->addChild('Clients', [
                'extras' => [
                    'icon' => 'fas fa-user-tie',
                    'routes' => [
                        'app_admin_client_create',
                        'app_admin_client_edit'
                    ]
                ],
                'label' => 'admin.clients',
                'route' => 'app_admin_client_list'
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
                'route' => 'app_admin_user_list'
            ]);

        return $menu;
    }
}
