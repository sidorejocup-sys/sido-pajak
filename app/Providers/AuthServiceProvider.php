<?php

namespace App\Providers;

use App\Models\Mutasi;
use App\Models\ObjekPajak;
use App\Models\Pembayaran;
use App\Models\Sppt;
use App\Models\SubjekPajak;
use App\Policies\SidoPajakPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        SubjekPajak::class => SidoPajakPolicy::class,
        ObjekPajak::class => SidoPajakPolicy::class,
        Sppt::class => SidoPajakPolicy::class,
        Mutasi::class => SidoPajakPolicy::class,
        Pembayaran::class => SidoPajakPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
