<?php

namespace App\Http\Controllers\Api\V1\Auth;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\ClientException;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use CloudCreativity\LaravelJsonApi\Document\Error\Error;
use CloudCreativity\LaravelJsonApi\Http\Controllers\JsonApiController;
use App\Models\Client;
use Illuminate\Support\Str;

class LoginTelegramController extends JsonApiController
{
    /**
     * Handle the incoming request.
     *
     * @param  LoginRequest  $request
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        try {
            $telegram_id = $request->t_id; // ID Telegram
            $telegram_name = $request->name; // Тут имя

            if (isset($request->tag)) {
                $telegram_tag = $request->tag; // Тут Тег  
            } else {
                $telegram_tag = "";
            }

            if (isset($request->image_uri)) {
                $telegram_image = $request->image_uri;
            } else {
                $telegram_image = "";
            }

            $generate_key = Str::random($length = 10)."-".rand(100000, 99999999);

            $find_client = Client::query()
                ->where('telegram_id', $telegram_id)
                ->first();

            if (isset($find_client->id)) {
                $find_client->authkey = $generate_key;
                $find_client->save();

                $type = "login";
            } else {
                $clients = new Client;

                $clients->telegram_id = $telegram_id;
                $clients->username = $telegram_name;
                $clients->tag = $telegram_tag;
                $clients->image = $telegram_image;
                $clients->authkey = $generate_key;

                $clients->save();
                $type = "register";
            }


            $response = array(
                'telegram_id' => $telegram_id,
                'telegram_name' => $telegram_name,
                'telegram_tag' => $telegram_tag,
                'type' => $type,
                'image' => $telegram_image,
                'phone' => $clients->phone,
                'difference_with_moscow' => $clients->difference_with_moscow,
                'auth_key' => $generate_key,
            );

            return $response;
        } catch (ClientException $e) {
            $error = json_decode((string)$e->getResponse()->getBody());

            return $this->reply()->errors([
                Error::fromArray([
                    'title' => 'Bad Request',
                    'detail' => $error->message,
                    'status' => '400',
                ])
            ]);
        }
    }

    public function checkLogin(Request $request)
    {
        $find_client = Client::query()
            ->where('telegram_id', $request->tg_id)
            ->first();
        if (isset($find_client->id)) {
            if ($find_client->telegram_activate == 0) {
                $user_active = "no";
            } else {
                $user_active = "yes";
            }
            $result = '{"status":"ok","message":"Успешно","user_active":"'.$user_active.'"}';
        } else {
            $result = '{"status":"error","message":"Ошибка, аккаунт не найден"}';
        }

        return $result;
    }

    public function activation(Request $request)
    {
        $find_client = Client::query()
            ->where('telegram_id', $request->tg_id)
            ->first();
        if (isset($find_client->id)) {
            if ($find_client->telegram_activate == 0) {
                $find_client->telegram_activate = 1;
                $find_client->save();

                $result = '{"status":"ok","message":"Успешно, аккаунт активирован"}';
            } else {
                $result = '{"status":"error","message":"Ошибка, аккаунт уже активен"}';
            }
        } else {
            $result = '{"status":"error","message":"Пройдите первичную авторизацию на нашем сайте и возвращайтесь для полной активации"}';
        }

        return $result;
    }
}
