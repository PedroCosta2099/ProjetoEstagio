<?php

namespace App\Http\Controllers\Customer;

use App\Models\User;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Status;
use App\Models\Payment;
use Illuminate\Http\Request;
use Setting,Auth,Validator,Redirect,Session,PDF,PdfMerger;

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
                        ->orderBy('created_at','desc')
                        ->with('status','payments')
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
        $orderPayments = [];
        foreach($orders as $order)
        {
            if(!in_array($orders,$orderIds, true)){
                array_push($orderIds,$order['id']);
            }
            if(!in_array($orders,$orderPayments, true)){
                array_push($orderPayments,$order['payment_id']);
            }
            
            
        }   
        $payments = Payment::with('payment_status')->whereIn('id',$orderPayments)->get();
        
        
        $orderlines = OrderLine::whereIn('order_id',$orderIds)
                                ->with('product','seller','order')
                                ->get();               
        return view('customer.about.info',compact('customer','addresses','orders','orderlines','count','payments'))->render();
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

    public function downloadPDF($id)
    {
        $orderlines = OrderLine::with('order','product')->where('order_id',$id)->get()->toArray();
        
        $order = Order::with('customers')->where('id',$id)->first();
        $customer = Customer::where('id',$order['customers']['id'])->first();
        $addresses = $customer->addresses()->get()->toArray();
        foreach($addresses as $address)
        {
            if($address['actual_billing_address'] == 1)
            {
                $billingAddress = $address;
            }
            if($address['actual_shipment_address'] == 1)
            {
                $shipmentAddress = $address;
            }
        }
        if($shipmentAddress == null)
        {
            $shipmentAddress = $billingAddress;
        }
        
        /*$pdf->WriteHTML('<h1>1</h1>');
        $pdf->WriteHTML('<table><thead><tr><th>id</th></tr></thead><tbody><td>'.$order['id'].'</td></tbody></table>');
        */
        $data = compact(
            'order',
            'orderlines',
            'billingAddress',
            'shipmentAddress'
        );
        $pdf = PDF::loadView('customer.pdf',$data);
        
        return $pdf->download();
        
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


    public function saveAddress(Request $request,$id)
    {
       
        $input = $request->all();
        
        $address = Address::findOrFail($id);
        $customer = Customer::where('id',Auth::guard('customer')->user()->id)->with('addresses')->first();
        
        $address->address = $input['address'];
        $address->postal_code = $input['postal_code'];
        $address->city = $input['city'];
        if(!array_key_exists('actual_shipment_address',$input))
        {
            $address->actual_shipment_address = 0;
        }
        else
        {
            foreach($customer->addresses as $customerAddress)
            {
                $customerAddress->actual_shipment_address = 0;
            }
            $address->actual_shipment_address = 1;
        }
        if(!array_key_exists('actual_billing_address',$input))
        {
            $address->actual_billing_address = 0;
        }
        else
        {
            foreach($customer->addresses as $customerAddress)
            {
                $customerAddress->actual_billing_address = 0;
            }
            $address->billing_address = 1;
            $address->actual_billing_address = 1;
        }
        $address->save();
        return Redirect::back()->with('success','Dados atualizados com sucesso!');
        
    }

    public function saveNewAddress(Request $request)
    {
        $input = $request->all();
        $address = new Address();
        $customer = Customer::where('id',Auth::guard('customer')->user()->id)->with('addresses')->first();
        $address->address = $input['address'];
        $address->postal_code = $input['postal_code'];
        $address->city = $input['city'];
        $address->shipment_address = 1;
        if(!array_key_exists('actual_shipment_address',$input))
        {
            $address->actual_shipment_address = 0;
        }
        else
        {
            foreach($customer->addresses as $customerAddress)
            {
                $customerAddress->actual_shipment_address = 0;
            }
            $address->actual_shipment_address = 1;
        }
        if(!array_key_exists('actual_billing_address',$input))
        {
            $address->actual_billing_address = 0;
        }
        else
        {
            foreach($customer->addresses as $customerAddress)
            {
                $customerAddress->actual_billing_address = 0;
            }
            $address->billing_address = 1;
            $address->actual_billing_address = 1;
        }
        $address->save();
        $address->customers()->detach();
        $address->customers()->attach($customer);
        return Redirect::back()->with('success','Morada gravada com sucesso!');
    }

    public function editBillingAddress($id)
    {
        $address = Address::where('id',$id)->with('customers')->first()->toArray();
        if($address['customers'][0]['id'] != Auth::guard('customer')->user()->id)
        {
            return view('errors.403');;
        }
        else
        {
            $previousPage = Session::get('previousPage');
            return view('customer.about.editBillingAddress',compact('address','previousPage'));
        }
        
    }

    public function createAddress()
    {
        $address = new Address();
        return view('customer.about.editShipmentAddress',compact('address'));
    }

    public function editShipmentAddress($id)
    {
        
        $address = Address::where('id',$id)->with('customers')->first()->toArray();
        if($address['customers'][0]['id'] != Auth::guard('customer')->user()->id)
        {
            return view('errors.403');;
        }
        else
        {
            $previousPage = Session::get('previousPage');
            return view('customer.about.editShipmentAddress',compact('address','previousPage'));
        }
        
    }

    public function savePreviousPage()
    {
        $this->previousPage = url()->previous();
        Session::put('previousPage',$this->previousPage);                          
        return  $this->previousPage;
    }

    public function shipmentAddresses()
    {   
        $id = Auth::guard('customer')->user()->id;
        $customer = Customer::findOrFail($id);
        $addresses = $customer->addresses()
                                ->get()
                                ->toArray();
        $count = 0;
        foreach($addresses as $address)
        {
            if($address['actual_shipment_address'] == 1)
                {
                    $count++;
                    $actualShipmentAddress = Address::findOrFail($address['id']);
                }
        }
        if($count == 0)
        {
            foreach($addresses as $address)
            {
                if($address['actual_billing_address'] == 1)
                {
                    $actualShipmentAddress = Address::findOrFail($address['id']);
                }
            }
        }
        $previousPage = Session::get('previousPage');
            return view('customer.about.shipmentAddresses',compact('actualShipmentAddress','addresses','previousPage')); 
       
    }
    

    public function updateActualShipmentAddress($id)
    {
        $customer = Customer::findOrFail(Auth::guard('customer')->user()->id);
        $addresses = $customer->addresses()
                                ->get();
                                
        foreach($addresses as $address)
        {
            $address['actual_shipment_address'] = 0;
            $address->save();
        }
        
        $actualShipmentAddress = Address::findOrFail($id);
        $actualShipmentAddress->shipment_address = 1;
        $actualShipmentAddress->actual_shipment_address = 1;
        $actualShipmentAddress->save();

        return Redirect::back()->with('success','Morada de Envio Principal Atualizada');

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
            $payment = Payment::where('id',$order->payment_id)->first();
            
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
            
            return view('customer.about.orderStatus',compact('order','status','orderStatus','failed','payment'))->render();
        }
        else
        {
            return view('errors.403');
        }
    }

}