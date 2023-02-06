<?php

namespace App\Http\Controllers\Api\V1\parser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class parserController extends Controller
{
    public static function getinfo(Request $request) {

        

    }

    public static function parseInfo(Request $request){
        $response = Http::post('https://flgplatform.ru/api/1688', [
            'url' => $request->url,
        ]);

        return $response;
    }
}
