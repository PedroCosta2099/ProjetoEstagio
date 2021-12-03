<?php

namespace App\Http\Controllers\Admin;

use App\Models\Core\Source;
use Request, Html, Form, View, Auth, Config, File;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Mail;
use App\Models\BroadcastPusher;
use Carbon\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    /**
     * The layout that should be used for responses
     * 
     * @var string 
     */
    protected $layout = 'admin.layouts.master';

    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'dashboard';

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if (!is_null($this->layout)) {
            View::share('sidebarActiveOption', $this->sidebarActiveOption);

            $this->layout = view($this->layout);
        }
    }

    public function callAction($method, $parameters)
    {
        $this->setupLayout();

        $response = call_user_func_array(array($this, $method), $parameters);

        if (is_null($response) && ! is_null($this->layout))
        {
            $response = $this->layout;
        }

        return $response;
    }
        
    /**
     * Set content used by the controller.
     * 
     * @param type $view
     * @param type $data
     * @return type
     */
    public function setContent($view, $data = [])
    {
        try {
            if (!is_null($this->layout))
            {
                return $this->layout->nest('child', $view, $data);
            }

            return view($view, $data);
        } catch (\Exception $e) {

            $text = $e->getMessage().' ---- FILE: ' . $e->getFile().' | LINHA: ' . $e->getLine().'<br/>';

            Mail::raw($text, function($message) {
                $message->to('geral@enovo.pt')
                    ->subject('Erro');
            });
        }

    }

    /**
     * Set the layout used by the controller.
     *
     * @param $name
     * @return void
     */
    protected function setLayout($name)
    {
        $this->layout = $name;
    }
}
