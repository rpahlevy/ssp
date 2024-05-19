<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Subscription;

use App\Jobs\SendMailJob;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendArticleNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'article:send-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send all new posts to subscribers which havent been sent yet';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $missedArticles = DB::select("SELECT s.id as subscription_id, u.email, a.id as article_id, a.title, a.description
            FROM subscriptions s
            CROSS JOIN articles a
            LEFT JOIN article_notifications an ON s.id = an.subscription_id AND a.id = an.article_id
            LEFT JOIN users u ON u.id = s.user_id
            WHERE a.website_id = s.website_id AND an.subscription_id IS NULL AND an.article_id IS NULL;");
        
        foreach ($missedArticles as $ma) {
            dispatch(new SendMailJob([
                'article_id' => $ma->article_id,
                'subscription_id' => $ma->subscription_id,
                'email' => $ma->email,
                'title' => $ma->title,
                'description' => $ma->description,
            ]));
        }
    }
}
