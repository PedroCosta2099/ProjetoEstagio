<?php

namespace App\Http\Controllers;

use App\Models\GatewayPayment\Base;
use App\Models\LogViewer;
use App\Models\Shipment;
use App\Models\ShipmentHistory;
use App\Models\ShipmentPayment;
use App\Models\ShippingStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Redirect;

class CallbackController extends Controller
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
    public function index(Request $request, $gateway = null)
    {
        $gateway = $gateway ? $gateway : 'eupago';

        //Log callback notification
        $trace = LogViewer::getTrace(null, 'Payment Callback Notification');
        Log::info(br2nl($trace));


        if($gateway == 'eupago') {
            $response = $this->euPago($request);
        }

        if($response['result']) {
            return redirect()->route('account.index')->with('success', 'Pagamento recebido com sucesso.');
        }

        return redirect()->route('account.index')->with('error', 'Não foi possível concluir o pagamento.');
    }

    /**
     * EuPago callback
     * @param Request $request
     */
    public function euPago(Request $request) {

        $result = true;
        $status = Base::STATUS_SUCCESS;
        if($request->has('eupago_status') && $request->get('eupago_status') == 'nok') {
            $result = false;
            $status = Base::STATUS_REJECTED;
        }

        //find payment and update payment
        $paymentCode = $request->get('identificador');
        $paymentCode = str_replace('PAG', '', $paymentCode);
        $payment = Base::where('code', $paymentCode)->first();

        if(!$payment) {
            $payment = new Base();
        }

        if($payment->status == Base::STATUS_SUCCESS) {
            return ['result' => true];
        }

        $payment->paid_at = date('Y-m-d H:i:s');
        $payment->status  = $status;
        $payment->save();

        //Send confirmation e-mail
        $payment->sendEmail();

        //update customer wallet balance (when target is null)
        if(@$payment->customer && $payment->target == 'Wallet') {

            if($payment->sense == 'credit') {
                $payment->customer->addWallet($payment->value);
            } else {
                $payment->customer->subWallet($payment->value);
            }
        }

        //update shipment status if is a shipment payment
        if($payment->target == 'Shipment') {
            $shipment = Shipment::where('customer_id', $payment->customer_id)
                        ->where('id', $payment->target_id)
                        ->first();

            if (!$shipment) {
                return Redirect::route('home.index')->with('error', 'Envio para pagamento não encontrado.');
            }


            if($payment->status == Base::STATUS_SUCCESS) {
                $shipmentStatus = ShippingStatus::PENDING_ID;
            } elseif($payment->status == Base::STATUS_REJECTED) {
                $shipmentStatus = ShippingStatus::CANCELED_ID;
            }

            $history = new ShipmentHistory();
            $history->shipment_id = $shipment->id;
            $history->status_id   = $shipmentStatus; //pending
            $history->save();

            $this->sendShipmentEmail($shipment);
        }

        return [
            'result' => $result
        ];
    }

    /**
     * Send Shipment e-mail
     * @param $shipment
     */
    public function sendShipmentEmail($shipment) {
        Mail::send('emails.payments.received', compact('shipment', 'customer'), function($message) use($shipment) {
            $message->to($shipment->customer->email)
                ->from(config('mail.from.address'), config('mail.from.name'))
                ->subject('Envio TRK'.$shipment->tracking_code.' - Pagamento recebido.');
        });
    }
}
