<?php

namespace App\Models\Actions;

use App\Jobs\StoreOrdersIntoAmoJob;
use App\Models\Order;

class StoreOrdersIntoAmoAction
{
    public function __invoke(Order $order)
    {
        StoreOrdersIntoAmoJob::dispatchSync($order);
    }
}