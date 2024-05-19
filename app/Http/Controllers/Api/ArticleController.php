<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use App\Models\Website;
use App\Models\Subscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\SendMailJob;

use App\Http\Resources\PostResource;

use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function index() {
        $articles = Article::latest()->paginate(5);

        return new PostResource(true, 'List 5 latest Articles', $articles);
    }

    public function store(Request $request) {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'website_url' => 'required',
            'title' => 'required',
            'description' => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // check if URL valid
        $url = filter_var($request->website_url, FILTER_SANITIZE_URL);
        if (filter_var($url, FILTER_VALIDATE_DOMAIN) === FALSE) {
            return response()->json([
                'website_url' => [
                    'Website URL is not valid!'
                ]
            ], 422);
        }

        $website = Website::firstOrCreate([
            'url' => $url,
        ]);;

        //create article
        $article = Article::create([
            'website_id' => $website->id,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        // get subscription list
        $subscriptions = Subscription::with('user')
            ->where('website_id', $website->id)
            ->get();
        foreach ($subscriptions as $sub) {
            dispatch(new SendMailJob([
                'email' => $sub->user->email,
                'title' => $article->title,
                'description' => $article->description,
            ]));
        }

        //return response
        return new PostResource(true, 'Article posted!', $article);
    }
}
