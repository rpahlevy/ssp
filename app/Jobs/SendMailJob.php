<?php

namespace App\Jobs;

use App\Models\ArticleNotification;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $data;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $email = $this->data['email'];
        $subject = $this->data['title'];
        try {
            Mail::raw($this->data['description'], function (Message $message) use ($email, $subject) {
                $message->to($email)
                ->subject($subject);
            });

            $an = ArticleNotification::create([
                'article_id' => $this->data['article_id'],
                'subscription_id' => $this->data['subscription_id'],
            ]);
        } catch (Exception $e) {
            $this->release(now()->addSeconds(10));
        }
    }
}
