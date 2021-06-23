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
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\OrderLine;
use App\Models\Status;
use App\Models\Payment;
use App\Models\PaymentStatus;
use App\Models\PaymentType;
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
        
   
    public function index()
    {
        
        $product = Product::first();
        return view('customer.cart.index',compact('product'))->render();
    }

    public function addToCart($id,$quantity)
    {
        $product = Product::where('id',$id)
                            ->first()
                            ->toArray();
        
        CartProvider::instance()->add(new Item($product['id'],$product['name'],$quantity,$product['price'],0,true,[],['image' => $product['filepath']]));
        return Redirect::back();
    }

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
                    
        $subTotal = number_format((float)$product->price * $quantity, 2, '.', '');
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
        return Redirect::route('customer.products.index')->with('success', 'Produtos removidos com sucesso.');
    }

    public function savePaymentMethod($id)
    {
        $paymentMethod = PaymentType::where('id',$id)
                                    ->first();
        Session::put('paymentMethodAux',$paymentMethod->id);
        $paymentMethodName = $paymentMethod->name;                            
        return  response()->json($paymentMethodName);
    }

    /**
     * Create order with order lines 
     */

    public function createOrder(){
        
        if(!Auth::check())
        {
            return Redirect::back()->with('error','Inicie sessão e tente novamente');
        }
        $IVA = 0.23;
        $user = Auth::user();
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
        //dd('a',$paymentMethod);
        
        $payment = new Payment();
        $paymentType = PaymentType::where('id',$paymentMethod)->first()->name;
        
        $payment->amount = $order->total_price;
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
        $order->save();
        CartProvider::instance()->destroy();
        Session::forget('paymentMethodAux');
        return view('customer.cart.finalizeOrder',compact('order','orderlines','payment'))->render();
    }
}

public function paymentMethod()
{
    $paymentMethods = PaymentType::get()->toArray();
    return view('customer.cart.payment',compact('paymentMethods'))->render();
}

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
    $orderTotal = CartProvider::instance()->getTotal();
}
    $paymentMethod = PaymentType::where('id',Session::get('paymentMethodAux'))->first();
    
    return view('customer.cart.resumeOrder',compact('cartProducts','orderTotal','paymentMethod'))->render();
}


}