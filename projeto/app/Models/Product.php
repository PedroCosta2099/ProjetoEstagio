<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use App\Models\Traits\FileTrait;
use Auth;

class Product extends BaseModel implements Sortable
{

    use SoftDeletes, SortableTrait,FileTrait;

    /**
     * Constant variables
     */
    const CACHE_TAG = 'cache_products';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * Default upload directory
     *
     * @const string
     */
    const DIRECTORY = 'uploads/products';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','filepath','filename','price','description','category_id','subcategory_id','quantity','vat','actual_price','actual_vat'
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
        'name' => 'Produto',
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

    public function category()
    {
        return $this->belongsTo('App\Models\Category','category_id');
    }
    public function seller()
    {
        return $this->belongsTo('App\Models\Seller','seller_id');
    }
    public function subcategory()
    {
        return $this->belongsTo('App\Models\SubCategory','subcategory_id');
    }

}