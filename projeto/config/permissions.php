<?php

return array(

    /**
     * Administrator role, to access all areas off the page without permission
     */
    'role' => [
        'admin'           => 'administrator',
        'agency'          => 'agencia',
        'seller'          => 'seller_admin',
        'guest_agency'    => 'agencia-convidada',
        'cashier_manager' => 'gestor-de-caixa',
        'platformer'      => 'plataformista',
        'operator'        => 'operador',
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
    ],

);
