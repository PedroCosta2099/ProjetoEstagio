<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Auth;
use Illuminate\Http\Request;
use Response;
use App\Models\Orderline;
use App\Http\Controllers\Admin\OrdersController;

class Order extends BaseModel implements Sortable
{

    use SoftDeletes, SortableTrait;

    /**
     * Constant variables
     */
    const CACHE_TAG = 'cache_orders';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','total_price','vat','shipment_address','status_id','customer_id','user_id','courier_id','delivery_fee','payment_id','sort'
    ];
    
    /**
     * Validator rules
     * 
     * @var array 
     */
    protected $rules = array(

    );
    
    /**
     * Validator custom attributes
     * 
     * @var array 
     */
    protected $customAttributes = array(
        'name' => 'Pedidos',
    );

    /**
     * Default sort column
     *
     * @var array
     */
    public $sortable = [
        'order_column_name' => 'sort'
    ];

    public function orderCols(){
        return $this->belongsToMany(Orderline::class,'order_lines','order_id','product_id');
    }

      /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    |
    | Define current model relationships
    */

    public function status()
    {
        return $this->belongsTo('App\Models\Status','status_id');
    }

    public function payments()
    {
        return $this->belongsTo('App\Models\Payment','payment_id');
    }

}
