<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subscriber;
use Mpociot\BotMan\BotMan;

class MessagesController extends Controller
{
    /**
     * Display message form.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('messages.index');
    }

    /**
     * Send message.
     *
     * @param Request $request
     */
    public function send(Request $request)
    {
    	$botman = app('botman');
        $botman->verifyServices(env('TOKEN_VERIFY'));

        $subscribers = Subscriber::all();
        foreach ($subscribers as $subscriber) {
        	$botman->say($request->input('message'), $subscriber->telegram_id);
        }
    }    
}
