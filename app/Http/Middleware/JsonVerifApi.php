<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Client;

class JsonVerifApi
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    protected $request;

    public function handle(Request $request, Closure $next)
    {
        if (!isset($request->tg_id) or !isset($request->api_key)) {
            $response = array(
                'error' => true,
                'message' => '403, Not authorized'
            );
        } else {
            $find_client = Client::query()
                ->where('telegram_id', $request->tg_id)
                ->where('authkey', '=', $request->api_key)
                ->first();
            if (!isset($find_client->id)) {
                $response = array(
                    'error' => true,
                    'message' => '403, Not authorized'
                );
            }
        }

        if (isset($response)) {
            return response()->json($response, 403);
        } else {
            return $next($request);
        }
    }
}
