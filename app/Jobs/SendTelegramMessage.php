<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mpociot\BotMan\BotMan;
use App\Subscriber;

class SendTelegramMessage implements ShouldQueue
{
    /**
     * The subscriber instance.
     *
     * @var Subscriber
     */
    public $subscriber;

    /**
     * The message instance.
     *
     * @var string
     */
    public $message;
        
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param  Subscriber  $subscriber
     * @param  string  $message
     * @return void
     */
    public function __construct(Subscriber $subscriber, $message)
    {
        $this->subscriber = $subscriber;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $botman = app('botman');
        $botman->verifyServices(env('TOKEN_VERIFY'));
        $botman->say($this->message, $this->subscriber->telegram_id);
    }
}
