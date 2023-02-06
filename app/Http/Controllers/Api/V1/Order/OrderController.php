<?php

namespace App\Http\Controllers\Api\V1\Order;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class OrderController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $findCLient = Client::query()
            ->where('telegram_id', $request->input('tg_id'))
            ->where('authkey', '=', $request->input('api_key'))
            ->firstOr(function () {
                abort(401, 'Unauthorized!');
            });

        try {
            //пусть будет ))
            $order = Order::query()
                ->create(
                    $request->only(array_merge((new Order())->getFillable(), ['client_id' => $findCLient->id]))
                );
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Something goes wrong'
            ], 403);
        }

        return response()
            ->json([
                'offers' => $order
            ]);
    }

    public function view(Request $request, int $orderId): JsonResponse
    {
        $findCLient = Client::query()
            ->where('telegram_id', $request->input('tg_id'))
            ->where('authkey', $request->input('api_key'))
            ->firstOr(function () {
                abort(401, 'Unauthorized!');
            });

        try {
            //пусть будет ))
            $order = Order::query()
                ->where('client_id', $findCLient->getKey())
                ->findOr($orderId, function () {
                    abort(404, 'Order not found!');
                });
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Something goes wrong'
            ], 403);
        }

        return response()
            ->json([
                'data' => $order
            ]);
    }

    public function list(Request $request): JsonResponse
    {
        //TODO: Add auth user check
        $findCLient = Client::query()
            ->where('telegram_id', $request->input('tg_id'))
            ->where('authkey', $request->input('api_key'))
            ->firstOr(function () {
                abort(401, 'Unauthorized!');
            });

        return response()
            ->json([
                'data' => Order::query()
                    ->where(
                        'client_id',
                        $findCLient->id
                    ) // TODO: Add current user id, also ensure user has role and filter orders based on view access policy
                    ->paginate()
            ]);
    }

    public function delete(Request $request, int $orderId): JsonResponse
    {
        //TODO: Add auth user check
        $findCLient = Client::query()
            ->where('telegram_id', $request->input('tg_id'))
            ->where('authkey', $request->input('api_key'))
            ->firstOr(function () {
                abort(401, 'Unauthorized!');
            });

        $order = Order::query()
            ->where('client_id', $findCLient->id)
            ->findOr($orderId, function () {
                abort(404, 'Order not found');
            });

        $order->delete();

        return response()
            ->json([
                'data' => 'Order deleted!',
            ], 204);
    }
}
