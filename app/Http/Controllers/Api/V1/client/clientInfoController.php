<?php

namespace App\Http\Controllers\Api\V1\client;

use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers\helpController;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\order;

class clientInfoController extends Controller
{
    public static function index(Request $request)
    {
        $find_client = Client::query()->where('telegram_id', $request->tg_id)->first();

        if (isset($find_client->id)) {
            $find_orders = Order::query()->where('client_id', $find_client->id)->get();

            if (isset($find_orders)) {
                $count_orders = count($find_orders);
            } else {
                $count_orders = 0;
            }

            $result = array(
                "client_info" => array(
                    "id" => $find_client->id,
                    "username" => $find_client->username,
                    "tag" => $find_client->tag,
                    "image" => $find_client->image,
                    "mail" => "",
                    "commission" => $find_client->commission,
                    "min_order_price" => $find_client->min_order_price
                ),
                "orders" => array(
                    "count" => $count_orders,
                ),
            );
        } else {
            $result = "{'status':'error','message':'Ошибка 404'}";
        }

        return $result;
    }


    public static function updateAll(Request $request)
    {
        $mail = false;
        $phone = false;
        $difference_with_moscow = false;

        $mail_value = "";
        $phone_value = "";
        $difference_with_moscow = "";

        $find_client = Client::query()
            ->where('telegram_id', $request->tg_id)
            ->first();

        if (isset($request->mail)) {
            if (helpController::checkValidMail($request->mail) and helpController::checkUseMail($request->mail)) {
                $find_client->mail = $request->mail;
                $find_client->save();

                $mail = true;
                $mail_value = $request->mail;
            }
        }

        if (isset($request->phone)) {
            if (helpController::checkValidPhone($request->phone) and helpController::checkUsePhone($request->phone)) {
                $find_client->phone = $request->phone;
                $find_client->save();

                $phone = true;
                $phone_value = $request->phone;
            }
        }

        if (isset($request->difference_with_moscow)) {
            $value = preg_replace('/[^\d-]+/', '', $request->difference_with_moscow);
            if (helpController::checkMoscowTime($value)) {
                $find_client->difference_with_moscow = $value;
                $find_client->save();

                $difference_with_moscow = true;
                $difference_with_moscow_value = $request->difference_with_moscow;
            }
        }

        $result = array(
            'success' => true,
            'edits' => array(
                'mail' => $mail,
                'mail_value' => $mail_value,
                'phone' => $phone,
                'phone_value' => $phone_value,
                'difference_with_moscow' => $difference_with_moscow,
                'difference_with_moscow_value' => $difference_with_moscow_value,
            ),
        );

        return $result;
    }

    public static function update(Request $request)
    {
        $find_client = Client::query()
            ->where('telegram_id', $request->tg_id)
            ->first();

        if (!isset($request->type) or !isset($request->value)) {
            $result = array(
                'success' => false,
                'message' => 'Ошибка, укажите тип запроса и его значение',
            );
        } else {
            switch ($request->type) {
                case 'mail':
                    if (helpController::checkValidMail($request->value)) {
                        if (helpController::checkUseMail($request->value)) {
                            $find_client->mail = $request->value;
                            $find_client->save();

                            $result = array(
                                'success' => true,
                                'message' => 'Успешно, mail обновлён',
                            );
                        } else {
                            $result = array(
                                'success' => false,
                                'message' => 'Ошибка, mail уже используется',
                            );
                        }
                    } else {
                        $result = array(
                            'success' => false,
                            'message' => 'Ошибка, укажите реальный mail',
                        );
                    }

                    break;

                case 'phonedifference_with_moscow':

                    $value = preg_replace('/[^\d-]+/', '', $request->value);

                    if (helpController::checkMoscowTime($value)) {
                        $find_client->difference_with_moscow = $value;
                        $find_client->save();

                        $result = array(
                            'success' => true,
                            'message' => 'Успешно, часовой пояс обновлён',
                        );
                    } else {
                        $result = array(
                            'success' => false,
                            'message' => 'Ошибка, часовой пояс от -10 до 10 по МСК',
                        );
                    }

                    break;

                case 'phone':
                    if (helpController::checkValidPhone($request->value)) {
                        if (helpController::checkUsePhone($request->value)) {
                            $find_client->phone = $request->value;
                            $find_client->save();

                            $result = array(
                                'success' => true,
                                'message' => 'Успешно, телефон обновлён',
                            );
                        } else {
                            $result = array(
                                'success' => false,
                                'message' => 'Ошибка, такой телефон уже используется',
                            );
                        }
                    } else {
                        $result = array(
                            'success' => false,
                            'message' => 'Ошибка, укажите реальный телефон',
                        );
                    }

                    break;


                default:
                {
                    $result = array(
                        'success' => false,
                        'message' => 'Ошибка, укажите реальный тип запроса',
                    );
                }
            }
        }

        return $result;
    }
}
