<?php

namespace App\Commands;

use Mpociot\BotMan\BotMan;
use App\Subscriber;
use Carbon\Carbon;

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
        if ($this->isDomainAvailable($domain) && $this->hasSSL($domain)) {
            $ssl = $this->getSSL($domain);

            $bot->reply('Issuer: ' . $ssl['issuer']['CN']);
            $validTo = Carbon::createFromTimestamp($ssl['validTo_time_t']);
            $now = Carbon::now();
            if ($validTo < $now) {
                $bot->reply('Is Valid: False');
                $bot->reply('Expiration date has passed');
            } else {
                $bot->reply('Is Valid: True');
                $daysLeft = $validTo->diffInDays($now);
                $bot->reply('Expired In: ' . $daysLeft);
            }
        } else {
            $bot->reply('Error! Check domain again.');
        }
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

    /**
     * Check if domain is available.
     *
     * @param string $domain
     * @return bool
     */
    protected function isDomainAvailable($domain)
    {
        return gethostbyname($domain) != $domain ? true : false;
    }

    /**
     * Fetch SSL data.
     *
     * @param string $domain
     * @return array
     */
    protected function getSSL($domain) {
        $stream = stream_context_create([
            "ssl" => ["capture_peer_cert" => true]
        ]);
        $read = stream_socket_client("ssl://{$domain}:443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $stream);
        $context = stream_context_get_params($read);
        return openssl_x509_parse($context["options"]["ssl"]["peer_certificate"]);
    }

    /**
     * Check if domain has SSL.
     *
     * @param string $domain
     * @return array
     */
    protected function hasSSL($domain) {
        $read = @fsockopen("ssl://{$domain}", 443, $errno, $errstr, 30);
        if (!$read) {
            return false;
        } else {
            return true;
            fclose($read);
        }
    }           
}