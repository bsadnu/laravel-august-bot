<?php

namespace App\Http\Controllers;

use App\Conversations\ExampleConversation;
use Illuminate\Http\Request;
use Mpociot\BotMan\BotMan;

class BotManController extends Controller
{
	/**
	 * Place your BotMan logic here.
	 */
    public function handle()
    {
    	$botman = app('botman');
        $botman->verifyServices(env('TOKEN_VERIFY'));


        // August Bot SSL info command
        $botman->hears('ssl-info {domain}', 'App\Commands\AugustCommands@handleSSLInfo');
        // August Bot Subscribe command
        $botman->hears('subscribe', 'App\Commands\AugustCommands@handleSubscribe');
        // August Bot Unsubscribe command
        $botman->hears('unsubscribe', 'App\Commands\AugustCommands@handleUnsubscribe');                

        $botman->listen();
    }

    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
    public function startConversation(BotMan $bot)
    {
        $bot->startConversation(new ExampleConversation());
    }
}
