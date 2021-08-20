<?php

namespace App\Http\Controllers\Customer;

use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Facades\Datatables;
use App\Models\Product;
use App\Models\User;
use App\Models\Customer;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\OrderLine;
use App\Models\Status;
use App\Models\Seller;
use App\Models\Payment;
use App\Models\Address;
use App\Models\PaymentStatus;
use App\Models\PaymentType;
use App\Http\Controllers\Customer\ProductsController;
use Auth;
use Validator;
use File,Croppa;
use Syscover\ShoppingCart\Facades\CartProvider;
use Syscover\ShoppingCart\Item;
use Syscover\ShoppingCart\TaxRule;

class CartController extends \App\Http\Controllers\Customer\Controller {

    private $paymentMethodAux;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }
        
   /**
    * Show cart 
    */
    public function index()
    {
        
        $product = Product::first();
        return view('customer.cart.index',compact('product'))->render();
    }


    /** 
     * Add to cart
     */
    public function addToCart($id,$quantity)
    {
        
            $product = Product::where('id',$id)->first();
            $productCategory = Category::where('id',$product->category_id)->first();
            $seller = Seller::where('id',$productCategory->seller_id)->first();
            $rememberSeller = Session::get('seller');
            
            if($rememberSeller == null)
            {
                 Session::put('seller',$seller->id);
                 
                 CartProvider::instance()->add(new Item($product['id'],$product['name'],$quantity,$product['actual_price'],0,true,[],['image' => $product['filepath']]));
                 return Redirect::back()->with('success','Item adicionado com sucesso');
            }
            elseif($rememberSeller != $seller->id)
            {
                
                return Redirect::back()->with('error','Só pode adicionar produtos do mesmo vendedor');
            }
            else
            {
                CartProvider::instance()->add(new Item($product['id'],$product['name'],$quantity,$product['actual_price'],0,true,[],['image' => $product['filepath']]));
                
                return Redirect::back()->with('success','Item adicionado com sucesso');
            }
            
    }


    /**
     * Cart items with details
     */

    public function cartItems()
    {
        if(CartProvider::instance()->getQuantity() == 0)
        {
            return Redirect::back()->with('error','Ainda não tem items no seu carrinho');
        }
        else{
        $productIds = [];
        $cartProducts = CartProvider::instance()->getCartItems();
        
        foreach($cartProducts as $cartProduct)
        {
            
        $product = Product::where('id',$cartProduct->id)
                                    ->get()
                                    ->toArray();
                                    
         array_push($productIds,$product[0]['id']);
        }
        
        $products = Product::whereIn('id',$productIds)->get()->toArray();
        $orderTotal = CartProvider::instance()->getTotal();
        
        return view('customer.cart.index',compact('cartProducts','products','orderTotal'))->render();
        }
    }


    /**
     * Update price and quantity
     */
    public function updatePrice($rowId,$id,$quantity)
    {
        
        $product = Product::where('id',$id)
                    ->first();
                    
        $subTotal = number_format((float)$product->actual_price * $quantity, 2, '.', '');
        CartProvider::instance()->setQuantity($rowId,$quantity);
        return response()->json($subTotal);
    }


    /**
     * Delete 1 product 
     */
    public function destroyRow($rowId)
    {
        CartProvider::instance()->remove($rowId);
        if(CartProvider::instance()->getQuantity() == 0)
        {
            Session::forget('seller');
            return Redirect::route('customer.products.index')->with('error','Ainda não tem items no seu carrinho');
        }
        else{
        return Redirect::route('customer.cart.index')->with('success', 'Produto removido com sucesso.');
        }
    }


    /**
     * Clean all cart
     */
    public function cleanCart()
    {
        CartProvider::instance()->destroy();
        Session::forget('seller');
        return Redirect::route('customer.products.index')->with('success', 'Produtos removidos com sucesso.');
    }

    /**
     * Save Payment Method
     */

    public function savePaymentMethod($id)
    {
        if(CartProvider::instance()->getQuantity() == 0)
        {
            return Redirect::back()->with('error','Ainda não tem items no seu carrinho');
        }
        else{
        $paymentMethod = PaymentType::where('id',$id)
                                    ->first();
        Session::put('paymentMethodAux',$paymentMethod->id);
        $paymentMethodName = $paymentMethod->name;                            
        return  response()->json($paymentMethodName);
    }
    }


    /**
     * Create order with order lines/create payment 
     */

    public function createOrder(){
        
        if(!Auth::guard('customer')->check())
        {
            return Redirect::back()->with('error','Inicie sessão e tente novamente');
        }
        $IVA = 0.23;
        $user = Auth::guard('customer')->user();
        
        if(CartProvider::instance()->getQuantity() == 0)
        {
            return Redirect::back()->with('error','Não pode criar um pedido sem items');
        }
        else {
        $order = $user->orders()->create([]);
        foreach(CartProvider::instance()->getCartItems() as $item){
           $order->orderCols()->attach($item->id,[
               'total_price' => $item->subtotal,
               'quantity' => $item->quantity
           ]);
        }
        
        $order->total_price = Orderline::where('order_id',$order->id)
                                        ->sum('total_price');

        $orderlines = Orderline::where('order_id',$order->id)
                                    ->get();
                                    
        $orderlineStatus = Status::where('name','like','PENDENTE')
                                    ->first();
        foreach($orderlines as $orderline){
            $product = Product::where('id',$orderline['product_id'])->first();
            $productCategory = Category::where('id',$product->category_id)->first();
            $seller = Seller::where('id',$productCategory->seller_id)->first();
        
            $orderline->seller_id = $seller->id;
            $orderline->created_at = date("Y-m-d H:i:s", time());
            $orderline->vat = number_format((float)$orderline->total_price * $IVA, 2, '.', '');
            $orderline->status_id = $orderlineStatus['id'];
            $orderline->save();
        }
        
        $status = Status::where('name','like','EM PREPARAÇÃO')
                            ->first();
        
        $order->status_id = $status['id'];                      
        $order->vat = number_format((float)$order->total_price * $IVA, 2, '.', '');
        
        
        
        $paymentMethod = Session::get('paymentMethodAux');
        
        
        $payment = new Payment();
        $paymentType = PaymentType::where('id',$paymentMethod)->first()->name;
        
        $payment->amount = $order->total_price + $seller->delivery_fee;
        $payment->payment_type_id = $paymentMethod;
        $payment->payment_status_id = PaymentStatus::where('name','like','PENDENTE')
                                            ->first()
                                            ->id;
        if($paymentType != "DINHEIRO")
        {
            $min = 100000000;
            $max = 999999999;
            
            do{
                $reference = rand($min, $max); 
                $referenceExists = Payment::where('reference', $reference)->first();
            }while($referenceExists !== null);
            $payment->reference = $reference;
            $payment->entity = 12345;
        }
        $payment->save();
        
        $order->payment_id = $payment->id;
        $customerId = Auth::guard('customer')->user()->id;
        $customer = Customer::with('addresses')->where('id',$customerId)->get()->toArray();
        $customerAddressesIds = [];
        foreach($customer[0]['addresses'] as $customerAddress)
        {
            if(!in_array($customerAddress, $customerAddressesIds, true)){
                    array_push($customerAddressesIds,$customerAddress['id']);
                }
            
        }
    
        $billingAddress = Address::whereIn('id',$customerAddressesIds)->where('actual_billing_address',1)->get()->toArray();
        $shipmentAddress = Address::whereIn('id',$customerAddressesIds)->where('actual_shipment_address',1)->get()->toArray();
        if(count($shipmentAddress) <= 0)
        {
            $shipmentAddress = $billingAddress;
        }

        $order->billing_address = $billingAddress[0]['id'];
        $order->shipment_address = $shipmentAddress[0]['id'];
        $order->delivery_fee = $seller->delivery_fee;
        
        $order->save();
        $data = compact('order','orderlines','payment','paymentMethod','billingAddress','shipmentAddress');
        return view('customer.cart.finalizeOrder',$data)->render();
    }
}
/**
 * Delete all cart
 */
public function deleteCartAndPayment()
{
    CartProvider::instance()->destroy();
    Session::forget('paymentMethodAux');
    return Redirect('feed');
}

/**
 * Get all payment methods/ get billing and shipment address
 */
public function orderInfo()
{
    
    if(CartProvider::instance()->getQuantity() == 0)
        {
            return Redirect::back()->with('error','Ainda não tem items no seu carrinho');
        }
    else{
    $paymentMethods = PaymentType::get()->toArray();
    $customerId = Auth::guard('customer')->user()->id;
    $customer = Customer::with('addresses')->where('id',$customerId)->get()->toArray();
    $customerAddressesIds = [];
    foreach($customer[0]['addresses'] as $customerAddress)
    {
        if(!in_array($customerAddress, $customerAddressesIds, true)){
                array_push($customerAddressesIds,$customerAddress['id']);
            }
            
    }
    
    $billingAddress = Address::whereIn('id',$customerAddressesIds)->where('actual_billing_address',1)->get()->toArray();
    $shipmentAddress = Address::whereIn('id',$customerAddressesIds)->where('actual_shipment_address',1)->get()->toArray();
    if(count($shipmentAddress) <= 0)
    {
        $shipmentAddress = $billingAddress;
    }
    //dd($billingAddress,$shipmentAddress);
    $allCustomerAddresses = Address::whereIn('id',$customerAddressesIds)->get()->toArray();
    
    return view('customer.cart.orderInfo',compact('paymentMethods','billingAddress','shipmentAddress'))->render();
}
}


/**
 * Resume Order with details
 */
public function resumeOrder()
{
    if(CartProvider::instance()->getQuantity() == 0)
    {
        return Redirect::back()->with('error','Ainda não tem items no seu carrinho');
    }
    else{
    $productIds = [];
    $cartProducts = CartProvider::instance()->getCartItems();
    
    foreach($cartProducts as $cartProduct)
    {
        
    $product = Product::where('id',$cartProduct->id)
                                ->get()
                                ->toArray();
                                
     array_push($productIds,$product[0]['id']);
    }
    
    $products = Product::whereIn('id',$productIds)->get()->toArray();
    $productToSeller = Product::whereIn('id',$productIds)->first();
    $productCategories = Category::where('id',$productToSeller->category_id)->first();
    $seller = Seller::where('id',$productCategories->seller_id)->first();
    $deliveryFee = $seller->delivery_fee;
    $productsTotal = CartProvider::instance()->total;
    $orderTotal = CartProvider::instance()->total+$deliveryFee;
    
    }   
    $paymentMethod = PaymentType::where('id',Session::get('paymentMethodAux'))->first();
    $customerId = Auth::guard('customer')->user()->id;
    $customer = Customer::with('addresses')->where('id',$customerId)->get()->toArray();
    $customerAddressesIds = [];
    foreach($customer[0]['addresses'] as $customerAddress)
    {
        if(!in_array($customerAddress, $customerAddressesIds, true)){
                array_push($customerAddressesIds,$customerAddress['id']);
            }
            
    }
    
    $billingAddress = Address::whereIn('id',$customerAddressesIds)->where('actual_billing_address',1)->get()->toArray();
    $shipmentAddress = Address::whereIn('id',$customerAddressesIds)->where('actual_shipment_address',1)->get()->toArray();
    if(count($shipmentAddress) <= 0)
    {
        $shipmentAddress = $billingAddress;
    }

    $data = compact(
        'cartProducts',
        'orderTotal',
        'paymentMethod',
        'billingAddress',
        'shipmentAddress',
        'deliveryFee',
        'productsTotal'
    );
    
    return view('customer.cart.resumeOrder',$data)->render();
}
}