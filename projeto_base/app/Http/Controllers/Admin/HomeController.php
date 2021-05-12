<?php

namespace App\Http\Controllers\Admin;

use App\Models\AddressBook;
use App\Models\Service;
use App\Models\ShipmentHistory;
use App\Models\Statistic;
use App\Models\Agency;
use App\Models\CalendarEvent;
use App\Models\Customer;
use App\Models\Shipment;
use App\Models\ShippingStatus;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Date, Session, DB, Setting, Response, File;

class HomeController extends \App\Http\Controllers\Admin\Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->setContent('admin.dashboard.index');
    }

    /**
     * Logout a remote login
     * GET /admin/users/selected/destroy
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function remoteLogout(Request $request) {

        if(Session::has('source_user_id')) {
            $user = User::findOrFail(Session::get('source_user_id'));

            Session::forget('source_user_id');

            $result = Auth::login($user);

            return Redirect::route('admin.dashboard')->with('success', 'Sessão iniciada com sucesso.');
        }

        return Redirect::back()->with('error', 'Nenhuma sessão remota iniciada.');
    }

    /**
     * Show denied page
     *
     * @return \Illuminate\Http\Response
     */
    public function denied(Request $request) {
        return $this->setContent('admin.partials.denied');
    }
}
