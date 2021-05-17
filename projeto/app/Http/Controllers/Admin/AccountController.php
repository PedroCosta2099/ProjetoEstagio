<?php

namespace App\Http\Controllers\Admin;

use App\Models\Notice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use File;

class AccountController extends \App\Http\Controllers\Admin\Controller {
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    /**
     * Change current user password
     * 
     * @return type
     */
    public function password() 
    {  
        $input = Input::only(['current_password', 'password', 'password_confirmation']);
        
        $rules = [ 
            'current_password' => 'required', 
            'password'         => 'required|confirmed'
        ];
        
        if(Validator::make($input, $rules)) {
            
            $user = Auth::user();

            
            if (!Hash::check($input['current_password'], $user->password)) {
                return Redirect::back()->with('error', 'A palavra-passe actual está incorreta.');
            }
        
            $user->password = $input['password'];
            $user->save();
            
            return Redirect::back()->with('success', 'Palavra-passe alterada com sucesso. Já pode utilizar a nova palavra-passe para iníciar sessão.');
            
        } else {
             return Redirect::back()->with('error', 'A nova palavra-passe e a confirmação da mesma não são iguais e é obrigatório o seu preenchimento.');
        }
    }

    /**
     * Set notice readed by customer
     *
     * @param $noticeId
     * @return \Illuminate\Http\JsonResponse
     */
    public function setNoticeReaded($noticeId = null) {

        $user = Auth::user();

        try {
            //set current notice id as readed
            if(!empty($noticeId)) {
                Auth::user()
                    ->notices()
                    ->updateExistingPivot($noticeId, ['readed' => 1]);
            }

            //update total notices
            $totalNotices = Auth::user()
                ->notices()
                ->wherePivot('readed', 0)
                ->count();

            $user->count_notices = $totalNotices;
            $user->save();

            return Redirect::back()->with('success', 'Aviso marcado como lido.');
            /*return response()->json([
                'result'        => true,
                'html'          => view('admin.partials.notices'),
                'total_notices' => $user->count_notices,
                'feedback'      => 'Marcado com lido com sucesso.',
            ]);*/
        } catch (\Exception $e) {
            /*return response()->json([
                'result'   => false,
                'feedback' => $e->getMessage()
            ]);*/
            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show full notice
     *
     * @param $noticeId
     * @return \Illuminate\Http\JsonResponse
     */
    public function showNotice($noticeId) {

        $notification = Notice::find($noticeId);

        return view('admin.notifications.show', compact('notification'))->render();
    }

    /**
     * Confirm payment notification
     */
    public function paymentConfirm() {

        $filename = storage_path() . '/enovo_payments.json';

        File::delete($filename);

        return Redirect::back();
    }
}
