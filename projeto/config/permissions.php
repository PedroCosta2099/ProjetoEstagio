<?php

return array(

    /**
     * Administrator role, to access all areas off the page without permission
     */
    'role' => [
        'admin'           => 'administrator',
        'seller'          => 'seller_admin',
        'gestor'          => 'gestor',
        'employee'        => 'seller_employee'
        
    ],


    /*
    |--------------------------------------------------------------------------
    | Application Permissions
    |--------------------------------------------------------------------------
    |
    | Here you may specify all your applications permissions, after add a new
    | permission run the command to sync them.
    | The permission name must be unique.
    |
    */

    'list' => [
        [
            "name" => "admin_users",
            "display_name" => "Administração - Utilizadores",
        ],
        [
            "name" => "admin_roles",
            "display_name" => "Administração - Perfis e permissões",
        ],
        [
            "name" => "admin_sellers",
            "display_name" => "Gestão Restaurante",
        ],
        [
            "name" => "orders",
            "display_name" => "Pedidos"
        ],
    ],

);
