<?php

namespace App\Jobs;

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
        // $email = new SendEmail($this->data);
        // Mail::to($this->data['email'])->send($email);
        $email = $this->data['email'];
        $subject = $this->data['title'];
        Mail::raw($this->data['description'], function (Message $message) use ($email, $subject) {
            $message->to($email)
                ->subject($subject);
        });
    }
}
