<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UrlCollection;
use App\Url;
use Illuminate\Http\Request;

class UrlApiController extends Controller
{
    public function index(Request $request)
    {
        $urls = Url::all();

        return UrlCollection::make($urls);
    }
}
