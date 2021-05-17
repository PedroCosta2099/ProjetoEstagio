<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Auth;

class Payment extends BaseModel implements Sortable
{

    use SoftDeletes, SortableTrait;

    /**
     * Constant variables
     */
    const CACHE_TAG = 'cache_payments';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payments';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','reference','amount','phone_number','payment_type_id','order_id','payment_status_id','entity'
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
        'name' => 'Pagamentos',
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

    public function payment_type()
    {
        return $this->belongsTo('App\Models\PaymentType','payment_type_id');
    }

    public function payment_status()
    {
        return $this->belongsTo('App\Models\PaymentStatus','payment_status_id');
    }
}
