<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Kutia\Larafirebase\Facades\Larafirebase;
use Notification;
use App\Notifications\SendPushNotification;
class HomeController extends Controller
{
    public function notification(Request $request){
        $request->validate([
            'title'=>'required',
            'message'=>'required'
        ]);
    
        try{
            $fcmTokens = User::whereNotNull('device_token')->pluck('device_token')->toArray();
    
            // Notification::send(null,new SendPushNotification($request->title,$request->message,$fcmTokens));
    
            /* or */
    
            //auth()->user()->notify(new SendPushNotification($title,$message,$fcmTokens));
    
            /* or */
    
            Larafirebase::withTitle("Sayed Sallon")
                ->withBody('Ok! we have a deal')
                ->sendMessage($fcmTokens);
    
            return redirect()->back()->with('success','Notification Sent Successfully!!');
    
        }catch(\Exception $e){
            report($e);
            return redirect()->back()->with('error','Something goes wrong while sending notification.');
        }
    }

    public function updateToken(Request $request){
        try{
            $request->user()->update(['device_token'=>$request->token]);
            return response()->json([
                'success'=>true
            ]);
        }catch(\Exception $e){
            report($e);
            return response()->json([
                'success'=>false
            ],500);
        }
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
}
