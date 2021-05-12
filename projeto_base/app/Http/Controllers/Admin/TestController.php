<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\FilesImporter\ImporterController;
use App\Models\Customer;
use App\Models\CustomerRecipient;
use App\Models\CustomerWebservice;
use App\Models\FleetGest\Cost;
use App\Models\FleetGest\Expense;
use App\Models\FleetGest\FuelLog;
use App\Models\FleetGest\Maintenance;
use App\Models\FleetGest\VehicleHistory;
use App\Models\GpsGateway\Base;
use App\Models\GpsGateway\Cartrack\Vehicle;
use App\Models\ImporterModel;
use App\Models\InvoiceGateway\KeyInvoice\Document;
use App\Models\InvoiceGateway\OnSearch\Item;
use App\Models\Logistic\Location;
use App\Models\Logistic\Product;
use App\Models\Logistic\ProductImage;
use App\Models\Logistic\ShippingOrder;
use App\Models\Provider;
use App\Models\ProviderService;
use App\Models\Shipment;
use App\Models\ShipmentHistory;
use App\Models\ShippingExpense;
use App\Models\ShippingStatus;
use App\Models\Sms\Sms;
use App\Models\Webservice\DbSchenker;
use App\Models\Webservice\Dhl;
use App\Models\Webservice\Mrw;
use App\Models\Webservice\TntExpress;
use App\Models\Webservice\ViaDirecta;
use App\Models\WebserviceConfig;
use App\Models\ZipCode;
use App\Models\ZipCodeProvince;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use LynX39\LaraPdfMerger\PdfManage;
use Mpdf\Mpdf;
use Setting, DB, Date, File, Hash, Response;
use PDF; // at the top of the file

class TestController extends \App\Http\Controllers\Admin\Controller {

    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['ability:' . config('permissions.role.admin') . ',settings']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {


         Artisan::call('sync:etcp', ['action' => 'import']);
         echo '<pre>'; Artisan::output();

         dd(1);


        /*$x = new \App\Models\InvoiceGateway\SageX3\Customer();
        //$resp = $x->getCustomer('120');
        $resp = $x->listsCustomers();

        $resp = $x->insertOrUpdateCustomer(
            '513087680',
            '120',
            'CASIMPER DISTRIBUIÇÃO LDA',
            'morada de teste',
            '1400-120',
            'Lisboa',
            '910222333',
            '',
            ''
        );

        dd($resp);*/

        //PROVIDERS
         /* $x = new \App\Models\InvoiceGateway\SageX3\Provider();
          //$resp = $x->listsProviders();
          $resp = $x->getProviderAddress('PT504530992');
          dd($resp);*/

        //FATURAS COMPRA
        /*$x = new \App\Models\InvoiceGateway\SageX3\Purchase();
        $resp = $x->listsInvoices();
        dd($resp);*/

        //LISTAR FATURAS VENDA
       /* $x = new \App\Models\InvoiceGateway\SageX3\Document();
        $resp = $x->getInvoice('FCN-M121/000001');
        dd($resp);*/

        $x = new \App\Models\InvoiceGateway\SageX3\Document();
        $resp = $x->getDocumentPdf('FCN-M120/000003');


        header('Content-Type: application/pdf');
        echo base64_decode($resp);
        exit;

        //dd($resp);

        //CRIAR FATURA
        /*$x = new \App\Models\InvoiceGateway\SageX3\Document();
        $resp = $x->createDraft('FCL', ['nif' => '513087680', 'docdate' => '2021-02-23', 'duedate' => '20210305', 'docref' => 'REF123', 'obs' => 'TESTE']);
        dd($resp);*/








        $country = 'gb';

        /*$filepath = public_path('postcodes/'.$country.'.xlsx');
        $excel = Excel::load($filepath, function($reader) use($country) { //faz a abertura do ficheiro

            $i = 0;
            $reader->each(function($row) use($country, &$data, &$i) {

                //$row['postal_code'] = str_pad($row['postal_code'], 2, '0', STR_PAD_LEFT);

                $data[] = [
                    'country'       => $row['country'],
                    'zip_code'      => $row['postcode'],
                    'postal_designation' => $row['town'],
                    'city'          => $row['town'],
                    'city_code'     => null,
                    'county_name'   => $row['region'],
                    'district_name' => $row['uk_region'],
                ];

                $i++;
                if(in_array($i, [1000])) {
                    ZipCode::insert($data);
                    $data = [];
                    $i = 0;
                }
            });
            ZipCode::insert($data);

            DD($data);
        });

        dd(1);*/
/*
        $data = [];
        foreach ($response as $row) {

            if(strlen($row['Postal_Code']) == 4) {
                $row['Postal_Code'] = '0' . $row['Postal_Code'];
            }

            $data[] = [
                'country' => 'it',
                'zip_code' => $row['Postal_Code'],
                'postal_designation' => $row['Place_Name'],
                'city' => $row['Place_Name'],
                'city_code' => null,
                'county_code' => $row['Admin_Code'],
                'district_code' => $row['Admin_Code2'],
            ];

        }

        try {
            ZipCode::insert($data);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }*/


dd(1);




       /* Artisan::call('sync:etcp', ['action' => 'import']);
        echo '<pre>'; Artisan::output();

        dd(1);*/


       /* $data = [
            'trackings' => [
                '',
                '',
                '',
            ]
        ];
        $url = 'http://www.viadirectanet.pt/webservices_prd/wstracking/Service.asmx?WSDL';


        $data = [
            'request' => [
                'Cod_Cliente' => '5791',
                'PEDIDO' => [
                    'WsSrvPedido' => [
                        ['Cod_Objecto'=> 'VD035000040586'],
                        ['Cod_Objecto'=> 'VD035000023566'],
                        ['Cod_Objecto'=> 'VD035000119886'],
                        ['Cod_Objecto'=> 'VD035000748817'],
                    ],
                ],
                'LOGIN' => [
                    'Password' => 'k742#k34$2',
                    'Username' => 'wsact24'
                ]
            ]
        ];

        $client = new \SoapClient($url);
        $result = $client->TRACKING($data);

        dd($result);
        $result = json_encode($result);
        $result = json_decode($result, true);
        $result = $result['TRACKINGResult']['WsSrvResposta'];

        if($result['Cod_Objeto'] == '000') {
            throw new \Exception($result['Desc_Tracking']);
        }

        $result = $this->mappingResult([$result], 'status');
        return $result;*/

        dd(1);

        $shipments = [];
        $shipments[] = ['date' => '2020-12-12'];
        //$shipments[] = ['date' => '2020-12-09'];
        //$shipments[] = ['date' => '2020-12-10'];
        //$shipments[] = ['date' => '2020-12-12'];

        $date = '2099-01-01';
        foreach($shipments as $shipment) {

            $date = $date < $shipment['date'] ? $date : $shipment['date'];

        }

        dd($date);
        //INSERIR CLIENTE
        /*$customer = Customer::find(17931);
        $x = new \App\Models\InvoiceGateway\SageX3\Customer();
        $res = $x->insertOrUpdateCustomer($customer->nif, $customer->code, $customer->name);*/

        //OBTER CLIENTES
/*        $x = new \App\Models\InvoiceGateway\SageX3\Customer();
        $res = $x->listsCustomers();*/

        //OBTER ARTIGOS
        /*$x = new \App\Models\InvoiceGateway\SageX3\Product();
        $res = $x->listsProducts();
        dd($res);*/

        //Obter compras
        /*$x = new \App\Models\InvoiceGateway\SageX3\Purchase();
        $res = $x->listsInvoices();
        dd($res);*/

        /*$x = new \App\Models\InvoiceGateway\SageX3\Document();
        $res = $x->getDocumentslist();
        dd($res);*/

        $x = new \App\Models\InvoiceGateway\SageX3\Document();
        $res = $x->getDocumentslist();
        dd(1);

        Artisan::call('invoice:schedule');
        echo '<pre>'; Artisan::output();


        dd('fim');
        /*$files = File::allFiles(public_path('moradas'));

        foreach ($files as $file) {

            $name =$file->getFilename();
            $code = str_replace('.xls', '', $name);

            $customerWebservice = CustomerWebservice::where('user', $code)->where('method', 'tipsa')->first();
            $customerId = @$customerWebservice->customer_id;

            $filepath = public_path('moradas/' . $name);

            $excel = Excel::load($filepath, function($reader) use($customerId) { //faz a abertura do ficheiro
                $reader->each(function($row) use($customerId) { //percorre cada row do ficheiro
                    $row = json_encode($row);
                    $row = json_decode($row, true);
                    //dd($row); //o plugin detecta os campos do header e associa o campo ao valor da linha

                    $x = CustomerRecipient::where('name', $row['nombre'])->first();

                    if (!@$x->exists && $customerId) {

                        $zp = $row['codigo_postal'];

                        $country = 'es';
                        if($zp[0] == '6') {
                            $zp = substr($zp, 1);
                            $country = 'pt';
                        }

                        $x = new CustomerRecipient();
                        $x->customer_id = $customerId;
                        $x->code = $row['codigo'];
                        $x->name = $row['nombre'];
                        $x->address = $row['direccion'];
                        $x->zip_code = $zp;
                        $x->city = $row['poblacion'];
                        $x->phone = $row['telefono'];
                        $x->country = $country;
                        $x->save();
                    }
                });
            });
        }
        dd($files);*/

        dd(1);

        $doc = new \App\Models\InvoiceGateway\OnSearch\Document();
        $doc = $doc->getDocument('201000004', 'GBC');
        dd($doc);

        $xxx = '83500';
        $xxx = substr($xxx, 0, 1) == '6' ? substr($xxx, 1) : $xxx;
dd($xxx);


        dd(2);
        /**
         * SYNC IMAGES
         */
        $images = [];
        for ($i=5 ; $i<=100 ; $i++) {

            $products = new Item();
            $products = $products->listsItems(1);

            dd($products);

            if(empty($products)) {
                $i=101;
            } else {
                foreach ($products as $key => $image) {
                    if (@$image['ItemImages']) {

                        $sku      = $image['ItemID'];
                        $serialNo = $image['SerialNum'];
                        $lote     = $image['Lots'];

                        //get product from DB
                        $product = Product::where('sku', $sku)
                            ->where('serial_no', $serialNo)
                            ->where('lote', $lote)
                            ->first();

                        //insert image if not exists
                        if(@$product && !$product->filepath) {

                            $folder =  ProductImage::DIRECTORY;

                            if(!File::exists(public_path($folder))) {
                                File::makeDirectory(public_path($folder));
                            }

                            $filecontent = $image['ItemImages'][0];
                            $filename  = $filecontent['Description'];

                            //$filename = strtolower(str_random(8).'.png');
                            $filepath = $folder.'/'.$filename;
                            $result = File::put(public_path($filepath), base64_decode($filecontent['ImageBase64']));

                            if($result) {
                                $productImage = new ProductImage();
                                $productImage->product_id = $product->id;
                                $productImage->filepath = $filepath;
                                $productImage->filename = $filename;
                                $productImage->is_cover = true;
                                $productImage->save();

                                $product->filehost = env('APP_URL');
                                $product->filepath = $filepath;
                                $product->filename = $filename;
                                $product->save();
                            }
                        }
                    }
                }
            }
        }


        dd($images);


        Artisan::call('sync:activos24');
        echo '<pre>'; Artisan::output();


        dd(2);

        $x = new Dhl();
        $shipment = Shipment::find(1372117);
        $x->updateHistory($shipment);

        dd(1);


/*
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://login.keyinvoice.com/FichaRecibo.php?SID=hjfsfsz78wp9p2q2vuk19&URLCodigoSerie=83",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array('BOTAO' => '3','URLCodigoMovimento' => '2450','CodigoMovimento' => '2450')
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        header('Content-Type: application/pdf');
        echo $response;
        exit;


        $client   = new \SoapClient('https://login.keyinvoice.com/API3_ws.php?wsdl', ['encoding' => 'UTF-8']);

        $response = $client->authenticate('108097n0fpa9951d74a7be9c4d1f04480ad7500dee');

        dd($response);*/

        dd(1111);



        $images = [];
        for($i=70 ; $i<80 ; $i++) {

            $item = new \App\Models\InvoiceGateway\OnSearch\Item();
            $allItems = $item->listsItems($i);

            dd($allItems);
            foreach ($allItems as $item) {
                if (!empty($item['ItemImages'])) {
                    $images[$item['ItemID']] = $item['ItemImages'];
                }
            }

            if ($images) {
                foreach ($images as $sku => $images) {

                }
            }
        }
        dd($images);









        $sms = new Sms(null, 'VodafoneApi');
        $sms->to            = '964026988';
        $sms->message       = 'mensagem de teste numero 2';
        $sms->source_id     = 1;
        $sms->source_type   = 'Shipment';
        $result = $sms->send();


        dd(1);







        $x = new \App\Models\InvoiceGateway\KeyInvoice\Base();
        dd($x->getDocumentation());

  /*      $customer = Customer::find(18700);
        $x = new \App\Models\InvoiceGateway\OnSearch\Customer();
        $xxx = $x->insertOrUpdateCustomer($customer);*/

        $doc = ShippingOrder::with('customer', 'lines.product')->find(5532);

        $x = new \App\Models\InvoiceGateway\OnSearch\Document();
        $xxx = $x->insertOrUpdateDocument($doc);

        dd($xxx);
/*
        $vehicles = new Vehicle();
        $vehicles->listVehicles();


        dd(2);*/


        $sms = new Sms(null, 'VodafoneApi');
        $sms->to            = '964026988';
        $sms->message       = 'mensagem de teste';
        $sms->source_id     = 1;
        $sms->source_type   = 'Shipment';
        $result = $sms->send();






        Artisan::call('sync:activos24');
        echo '<pre>'; Artisan::output();

        dd(1);
        $shipment = Shipment::find(1334656);
        $shipment->service_id = 8;

        /*$webservice = new Mrw();
        $webservice->saveShipment($shipment);*/

        $x = 'WS100486422PT';
        $x = '080112590978';
        $webservice = new Mrw();
       $x =  $webservice->getPod(null,null,$x);

       dd($x);
        //solicitud 0801100034820200801164800785
        //087002054555
        //http://sagec.mrw.es/Panel.aspx

        //GPS
        dd('fim');
        MONEY(1);
        $x = new Base();
        //$x = $x->listVehicles();//'29-OP-67'
        $vehiclesIds = '1055220';
        $x = $x->getRoute(1138564, '2020-07-17', '2020-07-17');
        dd($x);


        $shipments = Shipment::where('customer_id', 3976)->where('date', '2020-06-04')->get();

        $recipients = $shipments->pluck('recipient_id')->toArray();
        $recipients = CustomerRecipient::whereIn('id', $recipients)->get();

        $header = [
            'CODE',
            'NAME',
            'ADDRESS',
            'ZIP CODE',
            'CITY',
            'COUNTRY'
        ];

        Excel::create('Destinatarios', function($excel) use($recipients, $header) {

            $excel->sheet('Listagem', function ($sheet) use ($recipients, $header) {

                $sheet->row(1, $header);

                foreach ($recipients as $recipient) {

                    $rowData = [
                        $recipient->id,
                        $recipient->name,
                        $recipient->address,
                        $recipient->zip_code,
                        $recipient->city,
                        strtoupper($recipient->country)
                    ];
                    $sheet->appendRow($rowData);


                }
            });

        })->export('xls');



        $header = [
            'DATE',
            'SHIPPING TRK',
            'DEALER',
            'TOTAL PARCELS',
            'PARCEL NO',
            'PARCEL BARCODE',
            'GROSS WEIGHT',
            'CBM',
            'COD'
        ];

        Excel::create('Filename', function($excel) use($shipments, $header) {

            $excel->sheet('Listagem', function ($sheet) use ($shipments, $header) {

                $sheet->row(1, $header);

                foreach ($shipments as $shipment) {

                    for ($parcel=1 ; $parcel <= $shipment->volumes ; $parcel++) {


                        $barcode = $shipment->tracking_code . str_pad($shipment->volumes, 3, '0', STR_PAD_LEFT) . str_pad($parcel, 3, '0', STR_PAD_LEFT);

                        $rowData = [
                            $shipment->date,
                            'TRK' . $shipment->tracking_code,
                            $shipment->recipient_id,
                            $shipment->volumes,
                            $parcel,
                            $barcode,
                            $shipment->weight,
                            $shipment->volume_m3,
                            $shipment->charge_price
                        ];
                        $sheet->appendRow($rowData);
                    }

                }
            });

        })->export('xls');




/*
        $k = new Document();
        dd($k->getDocumentPdf(146, 'invoice'));*/



        /*$vehicles = [1086,1085,1083,1082,1081,1080,1079,1078,1077,1076,1075,1074,1073,1072,1071,1070,1069,1068,1067,1066,1065,1064,1063,1062,1061,1060,1059,1058,1057,1056,1055,1054,1053,1052,1051,1050,1049,1048,1047,1046,1045,1044,1041,1035,1034,1033,1032,1031,1030,1029,1028,1027,1026,1025,1024,1023,1022,1021];

        foreach ($vehicles as $vehicle) {

            DB::table('role_user')->insert([
                'user_id' => $vehicle,
                'role_id' => '3'
            ]);
        }*/




        dd(1);


        $filepath = 'public/01.xlsx'; //o ficheiro a carregar está na pasta public

        $excel = Excel::load($filepath, function($reader) { //faz a abertura do ficheiro
            $reader->each(function($row) { //percorre cada row do ficheiro

                $row = json_encode($row);
                dd($row); //o plugin detecta os campos do header e associa o campo ao valor da linha

                $time = $row['time'];
                $row['time'] = date("G:i", strtotime($time));

                dd($row['time']);
            });
        });


        dd(1);
        /*Artisan::call('run:daily-tasks');
        $output = Artisan::output();*/



        $filepath = 'public/01.xlsx';


        $excel = Excel::load($filepath, function($reader) {


            $it = 0;
            echo '<table style="width: 100px">';
            $reader->each(function($row) use(&$it){
                if($it==0) {
                    echo '<tr>';
                    echo '<td>'.$row['xpto'].'</td>';
                } else if($it==12) {
                    echo '<td>'.$row['xpto'].'</td>';
                    echo '</tr>';
                } else {
                    echo '<td>'.$row['xpto'].'</td>';
                }
                $it++;

                if($it == 13) {
                    $it = 0;
                }

            });

            echo '</table>';
        });
dd(2);












        set_time_limit (3000000);
        ini_set('max_execution_time', 3000000);
        ini_set('memory_limit', -1);

        $model = ImporterModel::find(71);

        $mapAttrs = [];
        foreach ($model->mapping as $key => $value) {
            if(!empty($value)) {

                $importerCollection = new ImporterController();
                $columnMapping = $importerCollection->getColumnMapping();

                if(in_array($value, array_keys($columnMapping))) {
                    $mapAttrs[$columnMapping[$value]] = $key;
                } else {
                    $value = (int) $value;
                    $mapAttrs[$value - 1] = $key;
                }
            }
        }


        $headerRow = [];
        foreach (trans('admin/importer.shipments') as $key => $value) {
            if($value['preview']) {
                $headerRow[] = $key;
            }
        }



        $customer = Customer::where('code', '2349')
            ->whereSource(config('app.source'))
            ->whereNull('customer_id')
            ->first(['id', 'code', 'agency_id', 'name','address', 'zip_code', 'city', 'country', 'phone', 'email']);

        $filepath = 'public/01.xlsx';

        $excel = Excel::load($filepath, function($reader) use($mapAttrs, $customer){

            $test = $reader->toArray();
            if(is_array(@$test[0][0])) { //multiple sheets
                $reader = $reader->first();
            }

            $shipmentsIds = [];
            $updateData = [];
            $insertArr = [];

            $i = 1;
            $hash = Hash::make(4);
            $reader->each(function($row) use($mapAttrs, $customer, &$shipmentsIds, &$updateData, &$insertArr, &$i, $hash) {

                if(!empty($row)) {

                    $errors = [];
                    $row = mapArrayKeys(array_values($row->toArray()), $mapAttrs);



                    unset($row['obs'],$row['recipient_country']);
                    $shipment = new Shipment();
                    $shipment->is_collection = 0;
                    $shipment->status_id     = ShippingStatus::ACCEPTED_ID;
                    $shipment->has_return    = null;
                    $shipment->date          = date('Y-m-d');
                    $shipment->agency_id     = '32';
                    $shipment->sender_agency_id     = '32';
                    $shipment->recipient_agency_id     = '32';

                    $row['volumes'] = 1;
                    $row['weight'] = 1;
                    $row['service_id'] = 269;
                    $row['provider_id'] = 125;

                    $row['sender_country']      = 'pt';
                    $row['recipient_country']   = 'pt';
                    $row['reference2']          = $hash;
                    $row['date']                = date('Y-m-d');
                    $row['customer_id']         = $customer->id;
                    $row['agency_id']           = $customer->agency_id;
                    $row['sender_agency_id']    = $customer->agency_id;
                    $row['recipient_agency_id'] = $customer->agency_id;
                    $row['sender_name']         = $customer->name;
                    $row['sender_address']      = $customer->address;
                    $row['sender_zip_code']     = $customer->zip_code;
                    $row['sender_city']         = $customer->city;
                    $row['sender_country']      = $customer->country;
                    $row['sender_phone']        = $customer->phone;
                    $row['obs']                 = 'Não é permitida a entrega em parcel';
                    $row['created_at']          = date('Y-m-d H:i:s');
                    $shipment->fill($row);
                    $insertArr[] = $shipment->toArray();

                    //$shipmentsIds[] = $shipment->id;
                }
            });

            Shipment::insert($insertArr);
            $shipments = Shipment::where('reference2', $hash)->get(['id', 'agency_id']);

            foreach ($shipments as $shipment) {
                $code = str_pad($shipment->agency_id, 3, "0", STR_PAD_LEFT);
                $code.= str_pad($shipment->id, 9, "0", STR_PAD_LEFT);

                $shipment->tracking_code = $code;
                $shipment->reference2    = null;
                $shipment->save();
            }

            dd(1);
            /**
             * Atualiza o estado do envio
             */
            /*foreach ($shipmentsIds as $shipmentsId) {
                $history = new ShipmentHistory();
                $history->shipment_id = $shipmentsId;
                $history->status_id   = ShippingStatus::ACCEPTED_ID;
                $history->save();
            }*/
        });




        //Artisan::call('migrate');

        dd(1);

        $shipment = Shipment::find(1164055);

        $x = new DbSchenker(null, null, null, 1);
        $shipment->provider_tracking_code = '200409-021548';

        //$x->getEstadoEnvioByTrk(null, null, 'DEDTM002313083');

        $trk = '200409-021548';
        $x->destroyShipment($shipment); //DEDTM002313083

        dd($x);

    /*    $operators = \App\Models\User::where('source', 'corridadotempo')->get();

        $i = 1;
        foreach ($operators as $operator) {
            if($operator->hasRole(config('permissions.role.admin'))) {
                $code = 'M'.str_pad($i, 3, '0', STR_PAD_LEFT);

                $operator->code = $code;
                $operator->save();
            }
        }

        dd($code);
        dd(2);*/

        //MIGRAR MODULO VIATURAS
        $providers = DB::connection('mysql_fleet')
                        ->table('fleet_providers')
                        ->where('source', 'asfaltolargo')
                        ->get();

        $mapping = [];
        foreach($providers as $provider) {
            $x = new \App\Models\Provider();
            $x->name = $provider->name;
            $x->type = 'others';
            $x->source = config('app.source');
            $x->agencies = ["1","16","59","66","67","72","96"];
            $x->category = $provider->type;
            $x->color = $provider->color;
            $x->old_id = $provider->id;
            $x->save();

            $mapping[$provider->id] = $x->id;
        }


        $x = FuelLog::with('provider')->get();
        foreach ($x as $item) {
            if(@$mapping[$item->provider_id]) {
                $item->provider_id = $mapping[$item->provider_id];
                $item->save();
            }
        }


        $x = Cost::with('provider')->get();
        foreach ($x as $item) {
            if(@$mapping[$item->provider_id]) {
                $item->provider_id = $mapping[$item->provider_id];
                $item->save();
            }
        }

        $x = Expense::with('provider')->get();
        foreach ($x as $item) {
            if(@$mapping[$item->provider_id]) {
                $item->provider_id = $mapping[$item->provider_id];
                $item->save();
            }
        }

        $x = Maintenance::with('provider')->get();
        foreach ($x as $item) {
            if(@$mapping[$item->provider_id]) {
                $item->provider_id = $mapping[$item->provider_id];
                $item->save();
            }
        }

        $x = VehicleHistory::with('provider')->get();
        foreach ($x as $item) {
            if(@$mapping[$item->provider_id]) {
                $item->provider_id = $mapping[$item->provider_id];
                $item->save();
            }
        }


        dd(1);


        $range = null;

        $sourceAgencies = [1];

        $date = new Date();
        $date = $date->subDays(45)->format('Y-m-d');

        $activeCustomers = Customer::filterSource()
            ->isProspect(false)
            ->isDepartment(false)
            ->whereIn('agency_id', $sourceAgencies)
            ->whereRaw('(select max(date) from shipments where shipments.customer_id = customers.id and deleted_at is null limit 0,1) >= "'.$date.'"')
            ->select(['id'])
            ->pluck('id')
            ->toArray();

        dd($activeCustomers);

        $customers = CustomerWebservice::remember(config('cache.query_ttl'))
            ->cacheTags(CustomerWebservice::CACHE_TAG)
            ->with('customer')
            ->isActive()
            ->whereHas('customer', function ($q) use ($activeCustomers) {
                $q->whereIn('id', $activeCustomers);
            });

        if(!is_null($range)) {
            $customers = $customers->skip($range[0])->take($range[1]);
        }


        $customers = $customers->get();



        $x = new \SoapClient('https://login.keyinvoice.com/API3_ws.php?wsdl');
        dd($x->__getFunctions());
        dd(1);


        $file = fopen(public_path("todos_cp.txt"), 'r');


        $i = 0;
        $start = 1159173;
        while(!feof($file)) {

            /*if($i >= $start) {
                $row = fgets($file);
                $row = rtrim($row);

                $row = explode(';', $row);

                $row[16] = trim($row[16]);
                $row[16] = strval($row[16]);

                $address = $row[5];

                if (!empty($row[6])) {
                    $address .= ' ' . $row[6];
                }

                if (!empty($row[7])) {
                    $address .= ' ' . $row[7];
                }

                if (!empty($row[8])) {
                    $address .= ' ' . $row[8];
                }

                if (!empty($row[9])) {
                    $address .= ' ' . $row[9];
                }

                if (!empty($row[10])) {
                    $address .= ' ' . $row[10];
                }


                $address = utf8_encode(trim($address));
                $address = empty($address) ? null : $address;

                $input = [
                    'district_code' => $row[0],
                    'county_code' => $row[1],
                    'city_code' => $row[2],
                    'city' => utf8_encode($row[3]),
                    'zip_code' => $row[14],
                    'zip_code_extension' => $row[15],
                    'postal_designation' => utf8_encode($row[16]),
                    'country' => 'pt',
                    'address' => $address,
                ];


                $zipCode = ZipCode::firstOrNew([
                    'zip_code' => $input['zip_code'],
                    'zip_code_extension' => $input['zip_code_extension'],
                    'country' => 'pt',
                    'ja' => 1
                ]);


                //if($zipCode->updated_at->format('Y-m-d') != date('Y-m-d')) {
                $zipCode->fill($input);
                $zipCode->file_row = $i + 1;
                $zipCode->ja = 2;
                $zipCode->save();
                //}
                $processed++;
                $start++;

            }*/

            $i++;
        }

        dd($i);
        fclose($file);



        dd(2);
    }

    public function mappingStatus($statusID) {

        if(in_array($statusID, [1,2,15,13,16,22])) {
            $status = 'EMB'; //recepcionado
        }

        else if(in_array($statusID, [9])) {
            $status = 'EMH'; //INCIDENCIA
        }

        else if(in_array($statusID, [8])) {
            $status = 'EMC'; //Anulado
        }

        else if(in_array($statusID, [7])) {
            $status = 'EMC'; //DEVOLVIDO
        }

        else if(in_array($statusID, [5, 12, 14])) {
            $status = 'EMI'; //ENTREGUE
        }

        else if(in_array($statusID, [9])) {
            $status = 'EMH'; //Entrega não Conseguida
        }

        else if(in_array($statusID, [43])) {
            $status = 'EMW'; //Avisado na Estação
        }

        else {
            $status = 'EMZ'; //Em Distribuição
        }

        return $status;
    }


    public function mappingIncidence($incidenceId) {

        $incidences = [
            '' => '22', //	EM DEVOLUÇÃO
            '3' => '10', //  ENDEREÇO INCORRECTO
            '16' => '11', //  DIFICULDADES EM LOCALIZAR DESTINATÁRIO
            '1' => '12', //	DESTINATÁRIO AUSENTE
            '13' => '13', //	RECUSADO
            '' => '131', //	CLIENTE DIZ NÃO TER ENCOMENDADO
            '' => '132', //	CLIENTE SOLICITA SERVIÇO DE INSTALAÇÃO
            '' =>  '133', //CLIENTE NÃO TEM INDICAÇÃO DE PAGAMENTO NA ENTREGA
            '' => '134', //	RECUSADO (PARCIALMENTE)
            '' => '135', //	RECUSADO (DESISTIU DA COMPRA)
            '' =>  '136', //	RECUSADO (PEDIDO DUPLICADO)
            '' =>  '137',//	RECUSADO (NÃO ADERIU)
            '' =>  '138',//	RECUSADO (JÁ ENVIOU)
            '6' =>  '14',//	DESTINATÁRIO PEDIU 2ª ENTREGA
            '' => '15',//	GREVE DO DESTINATÁRIO
            '7' => '16', //	ENTREGA EM FALTA (NÃO HOUVE SAÍDA)
            //'' => '17', //	OBJECTO MAL ENDEREÇADO
            '4' => '18', //	OBJECTO ESTRAGADO
            '' => '19',//	ARTIGOS PROIBIDOS / RESTRITOS
            '' => '20', //	ARTIGOS COM CÓDIGOS INCORRECTOS
            '32' => '21',//	CLIENTE NÃO EFECTUOU PAGAMENTO
            //'' => '23',//	FALECIDO
            '' => '24',//	OBJECTO NÃO DISTRIBUIDO POR MOTIVO DE FORÇA MAIOR
            '10' => '26',	//FERIADO MUNICIPAL
            '5' => '27',//	OBJECTO PERDIDO / EXTRAVIADO
            '' => '28',//	DESTINATÁRIO MUDOU-SE
            '' => '29',//	FOI DEIXADO NO APARTADO
            '' => '30',//	AGENDADO (agendamento efetuado pelos transportadores)
            '' => '31',//	REAGENDADO (reagendamento efetuado pelos transportadores)
            '' => '32',//	NÃO AGENDADO
            '' => '33',//	REAGENDADO (reagendamento efetuado pela MEO)
            '' => '40',//	RECOLHIDO NO CLIENTE
            '' => '99',//	OUTROS
            '' => '100',//	INICIO VISITA A CLIENTE (sempre que existe agendamento, deve ser enviado este motivo como confirmação de chegada ao destinatário)
            '' => '130',//	DADOS DE VENDA INCORRETOS
            '' => '139',//	CLIENTE DIZ NÃO TER ENCOMENDADO
            '' => '199',//	DEVOLUÇÃO SOLICITADA POR EMPRESA CLIENTE
            '' => '200',//	DOCUMENTAÇÃO RECOLHIDA NOK
            '' => '300',//	DOCUMENTAÇÃO PENDENTE DE CLIENTE - sempre que a documentação fica em posse do cliente para preenchimento e posterior recolha
        ];


        return @$incidences[$incidenceId] ? @$incidences[$incidenceId] : '99';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function syncShipments(Request $request) {

        $date = $request->get('date');
        if(empty($date)) {
            $date = date('Y-m-d');
        }

        $shipments = Shipment::whereIn('provider_id', [383])
            ->where('webservice_method', 'gls_zeta')
            ->where('date', $date)
            ->get();

        return Response::json($shipments->toArray());
    }

    /**
     * Convert a ZPL file to PDF
     */
    public function convertZPL2PDF($zpl, $trk, $volumes) {

        $listFiles = [];
        $curl = curl_init();

        foreach ($zpl as $zpl) {

            curl_setopt($curl, CURLOPT_URL, "http://api.labelary.com/v1/printers/8dpmm/labels/4x6/".$i."/");
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $zpl);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Accept: application/pdf"));
            $result = curl_exec($curl);

            $fileData = $result;

            $filepath = public_path() . '/uploads/labels/ups/' . $trk . '_label_' . $i . '.pdf';
            File::put($filepath, $fileData);

            $listFiles[] = $filepath;
        }

        curl_close($curl);


        /**
         * Merge files
         */
        $pdf = new \LynX39\LaraPdfMerger\PdfManage;
        foreach($listFiles as $filepath) {
            $pdf->addPDF($filepath, 'all');
        }

        /**
         * Save merged file
         */
        $filepath = '/uploads/labels/ups/' . $this->cliente_id .'_labels.pdf';
        $outputFilepath = public_path() . $filepath;
        $result = base64_encode($pdf->merge('string', $outputFilepath, 'P'));

        if($result) {
            foreach($listFiles as $item) {
                File::delete($item);
            }
        }

        return $result;
    }
}
