<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Auth;

class Address extends BaseModel implements Sortable
{

    use SoftDeletes, SortableTrait;

    /**
     * Constant variables
     */
    const CACHE_TAG = 'cache_address';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'addresses';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address','postal_code','city'
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
        'name' => 'Endereço',
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

    public function customers(){
        return $this->belongsToMany('App\Models\Customer');
    }
 
}
