<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Auth;

class OrderLine extends BaseModel implements Sortable
{

    use SoftDeletes, SortableTrait;

    /**
     * Constant variables
     */
    const CACHE_TAG = 'cache_orderlines';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'order_lines';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id','order_id','total_price','vat','quantity','status_id'
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
        'name' => 'Linhas de Pedidos',
    );

    /**
     * Default sort column
     *
     * @var array
     */
    public $sortable = [
        'order_column_name' => 'sort'
    ];

      /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    |
    | Define current model relationships
    */

    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Status','status_id');
    }
    public function seller()
    {
        return $this->belongsTo('App\Models\Seller','seller_id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order','order_id');
    }
}
