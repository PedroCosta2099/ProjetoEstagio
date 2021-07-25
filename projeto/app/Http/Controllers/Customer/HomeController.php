<?php

namespace App\Http\Controllers\Customer;

use App\Models\User;
use App\Models\Product;
use Setting;

class HomeController extends \App\Http\Controllers\Controller
{

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
        return $this->setContent('customer.test');
    }

}