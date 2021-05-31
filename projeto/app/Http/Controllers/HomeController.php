<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Setting;

class HomeController extends \App\Http\Controllers\Controller
{

    /**
     * The layout that should be used for responses
     *
     * @var string
     */
    protected $layout = 'layouts.master';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Login index controller
     *
     * @return \App\Http\Controllers\type
     */
    public function index() {

        $users = User::where('id', '>', 1)
                    ->orderBy('name', 'asc')
                    ->get(); //obtem todos os utilizadores cujo id > 1 ordenados por nome ascendente
                    
        return $this->setContent('customer.test', compact('users')); //chama a página HTML e envia a variavel users
    }

}