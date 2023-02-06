<?php

namespace App\Observers;

use App\Models\Actions\StoreOrdersIntoAmoAction;
use App\Models\Order;

class OrderObserver
{
    public function created(Order $order): void
    {

        app(StoreOrdersIntoAmoAction::class)($order);
    }
}
