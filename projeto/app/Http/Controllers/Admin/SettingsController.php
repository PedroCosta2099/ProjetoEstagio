<?php

namespace App\Http\Controllers\Admin;

use App\Models\Agency;
use App\Models\Budget\Budget;
use App\Models\Budget\Ticket;
use App\Models\CacheSetting;
use App\Models\CustomerType;
use App\Models\PriceTable;
use App\Models\Shipment;
use Setting, File, Auth, Response, Croppa, Date;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

use App\Models\ShippingStatus;
use App\Models\Billing;
use App\Models\Service;
use App\Models\Provider;

class SettingsController extends \App\Http\Controllers\Admin\Controller {

    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'admin_settings';

    /**
     * List of stoage directories
     *
     * @var array
     */
    protected $storageDirectories = [
        '/framework/cache',
        '/framework/views',
        '/framework/sessions',
        '/logs',
        '/debugbar',
        '/importer',
        '/invoices',
        '/keyinvoice-logs'
    ];

    /**
     * List of stoage directories
     *
     * @var array
     */
    protected $directoryModels = [
        '/uploads/agencies' => 'Agency',
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['ability:' . config('permissions.role.admin') . ',admin_settings']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $uploadDirectories  = [];

        $storageDirectories = [];
        foreach ($this->storageDirectories as $directory) {

            $folderName = explode('/', $directory);
            $folderName = end($folderName);

            $storageDirectories[$folderName] = ['filepath' => storage_path() . $directory];
        }


        $notificationSounds = [];
        $soundsDir = public_path('assets/sounds');
        $soundsFiles = collect(File::allFiles($soundsDir));
        $soundsFiles = $soundsFiles->sortBy(function ($file) {
            return $file->getFilename();
        });

        $soundId = 1;
        foreach ($soundsFiles as $soundFile) {

            $filename = $soundFile->getFilename();
            $filename = str_replace('.mp3', '', $filename);
            if(str_contains($filename, 'notification')) {
                $notificationSounds[$filename] = 'Notificação ' . $soundId;
                $soundId++;
            }
        }

        $pdfBgVertical = null;
        if(File::exists(public_path() . '/uploads/pdf/bg_v.png')) {
            $pdfBgVertical = '/uploads/pdf/bg_v.png';
        }

        $data = compact(
            'storageDirectories',
            'uploadDirectories',
            'pdfBgVertical'
        );

        return $this->setContent('admin.settings.settings.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $input = $request->except('_token');
    
        $input['customization_disable_notification_sound'] = $request->get('customization_disable_notification_sound', false);

        $allSettings = Setting::all();
        foreach($allSettings as $setting => $value) {
            Setting::forget($setting);
        }
        Setting::save();

        if($request->has('delete_pdf_bg')) {
            File::delete(public_path('uploads/pdf/bg_v.png'));
        }

        if($request->hasFile('pdf_bg')) {
            $file = $request->file('pdf_bg');
            $file->move(public_path('uploads/pdf'), 'bg_v.png');
        }

        $customizationFields = [];
        foreach ($input as $attribute => $value) {

            if(!empty($value) && $attribute == 'shipments_limit_search') {

                $today = Date::today();
                $minDate = $today->subMonth($value)->format('Y-m-d');

                $sourceAgencies = Agency::filterSource()->pluck('id')->toArray();
                $minShipment = Shipment::whereIn('agency_id', $sourceAgencies)
                    ->where('date', '>=', $minDate)->first(['id', 'date']);

                if($minShipment) {
                    CacheSetting::set('shipments_limit_search', $minShipment->id);
                    CacheSetting::set('shipments_limit_search_date', $minShipment->date);
                }

                if(hasModule('budgets')) {
                    $minBudget = Budget::where('source', config('app.source'))
                        ->where('date', '>=', $minDate)
                        ->first(['id', 'date']);

                    if($minBudget) {
                        CacheSetting::set('budgets_limit_search', $minBudget->id);
                    }
                }
            }

            if(!empty($value)) {
                Setting::set($attribute, $value);
            }


            if(str_contains($attribute, 'customization_')) {
                $attr = str_replace('customization_', '', $attribute);
                $customizationFields[$attr] = empty($value) ? null : $value;
            }
        }

        //Setting::save();

        //maintenance mode
        if(Setting::get('maintenance_mode')) {
            $ips = Setting::get('maintenance_ignore_ip');
            if(!empty($ips)) {
                $ips = explode(',', Setting::get('maintenance_ignore_ip'));
            }

            if(empty($ips) || !in_array(client_ip(), $ips)) { //force to set current ip
                $ips[] = client_ip();
            }

            touch(storage_path() . '/framework/down');
        } else {
            File::delete(storage_path() . '/framework/down');
        }

        //debug mode
        if(Setting::get('debug_mode')) {
            $ips = Setting::get('debug_ignore_ip');
            if(!empty($ips)) {
                $ips = explode(',', Setting::get('debug_ignore_ip'));
            }

            if(empty($ips) || !in_array(client_ip(), $ips)) { //force to set current ip
                $ips[] = client_ip();
            }

            $filename = storage_path() . '/framework/debug_ips';
            File::put($filename, implode(',', $ips));

        } else {
            File::delete(storage_path() . '/framework/debug_ips');
        }

        Setting::save();

        if(!empty($customizationFields)) {
            Auth::user()->setSettingArray($customizationFields);
        }

        return Redirect::back()->with('success', 'Alterações gravadas com sucesso');
    }


    /**
     * Upload File
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request) {

        $input = $request->except('_token');

        $allSettings = Setting::all();
        foreach($allSettings as $setting => $value) {
            Setting::forget($setting);
        }
        Setting::save();

        foreach ($input as $attribute => $value) {

            if(!empty($value)) {
                Setting::set($attribute, $value);
            }
        }
        Setting::save();

        return Redirect::back()->with('success', 'Alterações gravadas com sucesso');
    }

    /**
     * Clean store directories
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storageClean(Request $request) {

        $input = $request->folders;

        foreach ($input as $directory) {
            $file = new Filesystem();
            @$file->cleanDirectory($directory);

            File::put($directory.'/.gitignore', '');
        }

        return Redirect::back()->with('success', 'Dados limpos com sucesso.');
    }

    /**
     * Show directory files
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showDirectory(Request $request) {

        $directory = $request->directory;
        $storage   = $request->get('storage', false);

        $files = File::allFiles($directory);

        $files = array_sort($files, function($file) {
            return @$file->getFilename();
        });

        $directoryName = str_replace(public_path(), '', $directory);

        return view('admin.settings.settings.modals.files', compact('files', 'directoryName', 'storage'))->render();
    }

    /**
     * Show directory files
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroyFile(Request $request) {

        $file = $request->file;

        Croppa::delete($file);

        $result = File::delete(public_path() . $file);

        return Response::json([
            'result'   => $result
        ]);
    }

    /**
     * Clean all directory files
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cleanDirectory(Request $request) {

        $folder = $request->folder;

        $file = new Filesystem();
        @$file->cleanDirectory(public_path() . $folder);

        return Redirect::back()->with('success', 'Diretoria limpa com sucesso.');
    }

    /**
     * Compact all directory files
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function compactDirectory(Request $request) {

        $quality    = 40;
        $folder     = $request->folder;
        $directory  = public_path() . $folder;

        $files = File::allFiles($directory);

        $originalSize = $currentSize = 0;

        try {
            foreach ($files as $file) {

                $originalSize  += @$file->getSize();
                $filename       = @$file->getFilename();
                $absolutePath   = str_replace($filename, '', @$file->getRealPath());
                $realPath       = str_replace(' ', '%20', $absolutePath) . $filename;
                $mimeType       = mime_content_type($realPath);


                $isImage = false;
                if ($mimeType == 'image/jpeg') {
                    $image = imagecreatefromjpeg($realPath);
                    $isImage = true;
                } elseif ($mimeType == 'image/png') {
                    $image = imagecreatefrompng($realPath);
                    $isImage = true;
                }

                if($isImage) {

                    $imgSize = getimagesize($realPath);
                    if(@$imgSize[0] > 800 || @$imgSize[0] > 800) { //resize image if width or height are bigger than 800
                        $thumb = new \Imagick();
                        $thumb->readImage($realPath);
                        //$thumb->resizeImage(800, 600,\Imagick::FILTER_LANCZOS, 1);
                        $thumb->scaleImage(800, 0);
                        $thumb->writeImage($realPath);
                        $thumb->clear();
                        $thumb->destroy();
                    }

                    imagejpeg($image, $realPath, $quality);
                    clearstatcache();
                }

                $currentSize += filesize($realPath);
            }

            $compactSize = $originalSize - $currentSize;

            return Redirect::back()->with('success', 'Compressão com sucesso. Reduzidos ' . human_filesize($compactSize));

        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    /**
     * Clean all directory files
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function downloadFile(Request $request) {
        $file = $request->file;
        return response()->download($file);
    }


    /**
     * Load storage directory
     * @param Request $request
     * @return mixed|null|string
     * @throws \Throwable
     */
    public function loadDirectories(Request $request) {

        if(Auth::user()->hasRole('administrator')) {

            $directories = $this->storageDirectories;

            foreach ($directories as $directory) {
                $dirSize = 0;
                $countFiles = 0;

                foreach(File::allFiles(storage_path() . $directory) as $file) {
                    $countFiles++;
                    $dirSize += @$file->getSize();
                }

                $folderName = explode('/', $directory);
                $folderName = end($folderName);

                $storageDirectories[$folderName] = [
                    'filepath'  => storage_path() . $directory,
                    'size'      => $dirSize,
                    'count'     => $countFiles
                ];
            }

            $directories = File::directories(public_path() . '/uploads');

            foreach ($directories as $directory) {
                $dirSize = 0;
                $countFiles = 0;

                foreach(File::allFiles($directory) as $file) {
                    $countFiles++;
                    $dirSize += @$file->getSize();
                }

                $folderName = explode('/', $directory);
                $folderName = end($folderName);

                $uploadDirectories[$folderName] = [
                    'filepath'  => $directory,
                    'size'      => $dirSize,
                    'count'     => $countFiles
                ];
            }

            aasort($uploadDirectories, 'size', SORT_DESC);
            aasort($storageDirectories, 'size', SORT_DESC);
        }

        $data = [
            'uploads' => view('admin.settings.settings.partials.uploads_directory', compact('uploadDirectories'))->render(),
            'storage' => view('admin.settings.settings.partials.storage_directory', compact('storageDirectories'))->render()
        ];

        return response()->json($data);
    }
}
