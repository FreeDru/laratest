<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view order');
    }

    public function view(User $user, Order $order): bool
    {
        return $user->can('view order');
    }

    public function create(User $user): bool
    {
        return $user->can('create order');
    }

    public function update(User $user, Order $order): bool
    {
        return $user->can('edit order');
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->can('delete order');
    }
}