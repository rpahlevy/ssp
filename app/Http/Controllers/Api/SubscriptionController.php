<?php

namespace App\Http\Controllers\Api;

use App\Models\Website;
use App\Models\User;
use App\Models\Subscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\PostResource;

use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    public function store(Request $request) {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'website_url' => 'required',
            'email' => 'required',
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

        $user = User::firstOrCreate([
            'email' => $request->email,
        ]);

        $website = Website::firstOrCreate([
            'url' => $url,
        ]);

        //create susbcription
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'website_id' => $website->id,
        ]);

        //return response
        return new PostResource(true, 'Subscribed successfully!', [
            'email' => $request->email,
            'website_url' => $request->website_url,
        ]);
    }
}
