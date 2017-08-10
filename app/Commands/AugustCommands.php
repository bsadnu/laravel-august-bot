<?php

namespace App\Commands;

use Mpociot\BotMan\BotMan;
use App\Subscriber;
use Carbon\Carbon;
use Spatie\SslCertificate\SslCertificate;
use Spatie\SslCertificate\Exceptions\CouldNotDownloadCertificate;

/**
* August bot commands class.
*/
class AugustCommands
{
    /**
     * SSLInfo command handler.
     *
     * @param BotMan $bot
     * @param string $domain
     * @return void
     */    
    public function handleSSLInfo(BotMan $bot, $domain)
    {
        try {
            $ssl = SslCertificate::createForHostName($domain);
        } catch (CouldNotDownloadCertificate $e) {
            $bot->reply('Error! Check domain again.');
        }

        $bot->reply('Issuer: ' . $ssl->getIssuer());
        $bot->reply('Expired In: ' . $ssl->expirationDate()->diffInDays());
        $bot->reply('Is Valid: ' . $ssl->isValid() ? 'True' : 'False');      
    }

    /**
     * Subscribe command handler.
     *
     * @param BotMan $bot
     * @return void
     */ 
    public function handleSubscribe(BotMan $bot)
    {
        $user = $bot->getUser();

        if (Subscriber::where('telegram_id', '=', $user->getId())->exists()) {
            $bot->reply('Failed! You have already subscribed.');
        } else {
            $subscriber = new Subscriber;
            $subscriber->telegram_id = $user->getId();
            $subscriber->username = $user->getUsername();
            $subscriber->first_name = $user->getFirstName();
            $subscriber->last_name = $user->getLastName();
            $subscriber->save();
            $bot->reply('Success! You have subscribed.');
        }
    }

    /**
     * Unsubscribe command handler.
     *
     * @param BotMan $bot
     * @return void
     */
    public function handleUnsubscribe(BotMan $bot)
    {
        $user = $bot->getUser();
        $subscriber = Subscriber::where('telegram_id', $user->getId());

        if ($subscriber->exists()) {
            $subscriber->delete();
            $bot->reply('Success! You have unsubscribed.');
        } else {
            $bot->reply('Failed! You haven\'t subscribed yet.');
        }
    }        
}