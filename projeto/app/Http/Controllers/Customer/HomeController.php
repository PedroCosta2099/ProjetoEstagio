<?php

namespace App\Http\Controllers\Customer;

use App\Models\User;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Status;
use Illuminate\Http\Request;
use Setting,Auth,Validator,Redirect;

class HomeController extends \App\Http\Controllers\Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'logout']);
    }

    /**
     * Login index controller
     *
     * @return \App\Http\Controllers\type
     */
    public function index()
    {
        return $this->setContent('customer.about.info');
    }

    public function about()
    {
        $id = Auth::guard('customer')->user()->id;
        $customer = Customer::findOrFail($id);
        $addresses = $customer->addresses()
                              ->get()
                              ->toArray();
        $orders = Order::where('customer_id',$id)
                        ->with('status')
                        ->get();
        $count = 0;
        foreach($addresses as $address)
        {
            if($address['actual_shipment_address'])
                {
                    $count++;
                }

        }  
        
        $orderIds = [];
        foreach($orders as $order)
        {
            if(!in_array($orders,$orderIds, true)){
                array_push($orderIds,$order['id']);
            }
            
        }      
        
        $orderlines = OrderLine::whereIn('order_id',$orderIds)
                                ->with('product','seller','order')
                                ->get();

        return view('customer.about.info',compact('customer','addresses','orders','orderlines','count'))->render();
    }

    public function editPersonalData()
    {
        $id = Auth::guard('customer')->user()->id;
        $customer = Customer::where('id',$id)->first();
        $data = compact(
            'customer'
        );
        return view('customer.about.personalDataEdit',$data);
    }

    public function savePersonalData(Request $request)
    {
        $input = $request->all();
       
        $id = Auth::guard('customer')->user()->id;
        $customer = Customer::where('id',$id)->first();
        $rules = [];
        if ($customer->exists && empty($input['password'])) {
            $rules['name']  = 'required';
            $rules['email'] = 'required|email|unique:customers,email,' . $customer->id;
            if($customer->phone != $input['phone'])
            {
                $rules['phone'] = 'required|unique:customers,phone|min:9|max:9';
            }
            else
            {
                
                $rules['phone'] = 'required|min:9|max:9';
            }
            if($customer->nif != $input['nif'])
            {
                
            $rules['nif'] = 'required|unique:customers,nif|min:9|max:9';
            }
            else
            {
                $rules['nif'] = 'required|min:9|max:9';
            }
            
        } elseif($customer->exists) {
            
            $rules['name']  = 'required';
            $rules['email'] = 'required|email|unique:customers,email,' . $customer->id;
            if($customer->phone != $input['phone'])
            {
                $rules['phone'] = 'required|unique:customers,phone|min:9|max:9';
            }
            else
            {
                
                $rules['phone'] = 'required|min:9|max:9';
            }
            if($customer->nif != $input['nif'])
            {
                
            $rules['nif'] = 'required|unique:customers,nif|min:9|max:9';
            }
            else
            {
                $rules['nif'] = 'required|min:9|max:9';
            }

            
        }
            $validator = Validator::make($input, $rules);
           
        if($validator->passes())
        {
            if(empty($input['password']))
            {
                $customer->name = $input['name'];
                $customer->email = $input['email'];
                $customer->phone = $input['phone'];
                $customer->nif = $input['nif'];
                $customer->save();
                return Redirect::route('customer.editPersonalData')->with('success','Dados gravados com sucesso');
            }
            elseif($input['password'] == $input['password_confirmation'])
            {
                $customer->password = bcrypt($input['password']);
                $customer->name = $input['name'];
                $customer->email = $input['email'];
                $customer->phone = $input['phone'];
                $customer->nif = $input['nif'];
                $customer->save();
                return Redirect::route('customer.editPersonalData')->with('success','Dados gravados com sucesso');
            }
            else
            {
                return Redirect::back()->withInput()->with('error', 'Confirme novamente a password');
            }
        }
        else
        {
            return Redirect::back()->withInput()->with('error', $validator->errors()->first());
        }
    }

    public function orderStatus($id)
    {
    
        $order = Order::where('id',$id)
                        ->first();
                        
        if(Auth::guard('customer')->user()->id == $order['customer_id'])
        {
            $orderStatusId = $order['status_id'];
            $orderStatus = Status::where('id',$orderStatusId)
                                    ->first();
            if($orderStatus->name == "FALHA NA ENTREGA")
            {
                $failed = 1;
            }
            else
            {
                $failed = 0;
                $status = Status::where('name','not like','FALHA NA ENTREGA')
                                ->orderBy('sort','asc')
                                ->get()
                                ->toArray();
            }
            
            return view('customer.about.orderStatus',compact('order','status','orderStatus','failed'))->render();
        }
        else
        {
            return view('errors.403');
        }
    }

}