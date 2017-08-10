<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subscriber;
use Mpociot\BotMan\BotMan;
use App\Jobs\SendTelegramMessage;
use Carbon\Carbon;

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
        $subscribers = Subscriber::all();

        foreach ($subscribers as $subscriber) {
	    	$job = (new SendTelegramMessage($subscriber, $request->input('message')));
	        dispatch($job);
        }
    }    
}
