<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Tag;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use App\Policies\OrderPolicy;
use App\Policies\TagPolicy;
use App\Policies\ItemPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use Laravel\Passport\Passport;
use App\Policies\CategoryPolicy;
use App\Policies\PermissionPolicy;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        Permission::class => PermissionPolicy::class,
        Category::class => CategoryPolicy::class,
        Tag::class => TagPolicy::class,
        Item::class => ItemPolicy::class,
        Order::class => OrderPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
    }
}
