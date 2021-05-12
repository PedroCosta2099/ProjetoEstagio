<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Auth;

class Brand extends BaseModel implements Sortable
{

    use SoftDeletes, SortableTrait;

    /**
     * Constant variables
     */
    const CACHE_TAG = 'cache_brands';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'brands';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type'
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
        'type' => 'Modelo',
        'name' => 'Marca'
    );

    /**
     * Default sort column
     *
     * @var array
     */
    public $sortable = [
        'order_column_name' => 'sort'
    ];

}
