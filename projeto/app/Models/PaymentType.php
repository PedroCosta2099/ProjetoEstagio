<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use App\Models\Traits\FileTrait;
use Auth;

class PaymentType extends BaseModel implements Sortable
{

    use SoftDeletes, SortableTrait,FileTrait;

    /**
     * Constant variables
     */
    const CACHE_TAG = 'cache_paymenttypes';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payment_types';

        /**
     * Default upload directory
     *
     * @const string
     */
    const DIRECTORY = 'uploads/paymenttypes';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','filepath','filename'
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
        'name' => 'Método de Pagamento',
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
