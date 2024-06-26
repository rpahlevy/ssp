<?php

namespace App\Http\Controllers\Api;

use App\Models\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\PostResource;

use Illuminate\Support\Facades\Validator;

class WebsiteController extends Controller
{
    public function index() {
        $websites = Website::latest()->paginate(5);

        return new PostResource(true, 'List 5 latest Websites', $websites);
    }

    public function store(Request $request) {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'url' => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // check if URL valid
        $url = filter_var($request->url, FILTER_SANITIZE_URL);
        if (filter_var($url, FILTER_VALIDATE_DOMAIN) === FALSE) {
            return response()->json([
                'website_url' => [
                    'Website URL is not valid!'
                ]
            ], 422);
        }

        // check if website exist
        $website = Website::where('url', $url)
            ->first();
        if ($website) {
            return response()->json([
                'url' => [
                    'Website already exists'
                ]
            ], 422);
        }

        //create website
        $website = Website::create([
            'url' => $url,
        ]);

        //return response
        return new PostResource(true, 'Website added!', $website);
    }
}
