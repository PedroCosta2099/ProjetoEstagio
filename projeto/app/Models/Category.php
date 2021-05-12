<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Auth;

class Category extends BaseModel implements Sortable
{

    use SoftDeletes, SortableTrait;

    /**
     * Constant variables
     */
    const CACHE_TAG = 'cache_categories';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'categories';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','seller_id'
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
        'name' => 'Categoria',
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
    
    public function seller()
    {
        return $this->belongsTo('App\Models\Seller','seller_id');
    }
}
