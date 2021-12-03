<?php

namespace App\Providers;

use App\Models\Agency;
use App\Models\CalendarEvent;
use App\Models\Cashier\Movement;
use App\Models\ChangeLog;
use App\Models\Customer;
use App\Models\CustomerWebservice;
use App\Models\FileRepository;
use App\Models\FleetGest\Cost;
use App\Models\FleetGest\Expense;
use App\Models\FleetGest\FixedCost;
use App\Models\FleetGest\FuelLog;
use App\Models\FleetGest\Incidence;
use App\Models\FleetGest\Maintenance;
use App\Models\FleetGest\Provider;
use App\Models\FleetGest\TollLog;
use App\Models\FleetGest\Vehicle;
use App\Models\FleetGest\VehicleHistory;
use App\Models\Invoice;
use App\Models\ProductSale;
use App\Models\PurchaseInvoice;
use App\Models\RefundControl;
use App\Models\RefundControlAgency;
use App\Models\Service;
use App\Models\ShipmentExpense;
use App\Models\WebserviceConfig;
use View, Auth, Setting, App;
use Illuminate\Support\ServiceProvider;
use App\Models\ShipmentHistory;
use App\Models\Shipment;
use App\Models\CustomerType;
use App\Models\Product;
use App\Models\ShippingExpense;
use App\Models\Provider as ShippingProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        require resource_path() . '/helpers/macros.php';
        require resource_path() . '/helpers/functions.php';
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
    }
    
}
