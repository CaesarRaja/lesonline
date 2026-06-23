<?php

namespace App\Providers;

use App\Models\Material;
use App\Models\Transaction;
use App\Models\User;
use App\Policies\MaterialPolicy;
use App\Policies\TransactionPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::policy(Transaction::class, TransactionPolicy::class);
        Gate::policy(Material::class, MaterialPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
    }
}
