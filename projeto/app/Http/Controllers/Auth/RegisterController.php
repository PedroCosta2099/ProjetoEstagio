<?php

namespace App\Http\Controllers\Auth;

use App\Models\Customer;
use App\Models\Address;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Validator, Setting, Mail, Auth, Redirect;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/area-cliente';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    
        
    /**
     * Custom login guard
     * 
     * @return type
     */
    protected function guard()
    {
        return Auth::guard('customer');
    }

    /**
     *
     * Login index controller
     *
     */
    public function index() {

        return $this->setContent('customer.auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:customers,email',
            'password' => 'required|min:6|confirmed',
            'nif' => 'required|digits:9|unique:customers,nif,',
            'phone' => 'required|digits:9|unique:customers,phone'
        ]);
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    
    protected function create(Request $request)
    {

        $input = $request->toArray();
        
        $customer = Customer::where('email', $input['email'])->first();
        
        if($customer) {
            
            return Redirect::back()->withInput()->with('error', 'Já existe outro utilizador com o e-mail indicado. Se perdeu a palavra-passe, experimente a opção recuperar palavra-passe');
        }

        if(!str_contains($input['name'], ' ') || !str_contains($input['address'], ' ')) {
            
            return Redirect::back()->withInput()->with('error', 'Dados inválidos.');
        }

        try {
            $customer = new Customer();
            $address = new Address();
            
            $customer->name             = trim($input['name']);
            $customer->email            = strtolower(trim($input['email']));
            $customer->phone            = $input['phone'];
            $customer->nif              = $input['nif'];
            $customer->password         = bcrypt($input['password']);

            $address->address          = $input['address'];
            $address->postal_code      = $input['postal_code'];
            $address->city             = $input['city'];
            //dd($request->name,$request->email,$request->password,$request->nif,$request->phone);
           // validate doesn't work correctly
            $validate = Validator::make(array($request->name,$request->email,$request->password,$request->nif,$request->phone), [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:customers,email',
                'password' => 'required|min:6|confirmed',
                'nif' => 'required|digits:9|unique:customers,nif,',
                'phone' => 'required|digits:9|unique:customers,phone'
            ]);

            if($validate)
            {
            $customer->save();
            $address->save();
            $customer->addresses()->attach($address);
            //start session
            
            Auth::guard('customer')->login($customer);

            return Redirect::route('customer.products.index')->with('success', 'Conta criada com sucesso!');
            }


        } catch (\Exception $e) {
            dd($e);
            return Redirect::back()->withInput()->with('error', 'Erro ao criar conta. Não foi possível criar a sua conta devido a um erro desconhecido.');
        }
    }
}