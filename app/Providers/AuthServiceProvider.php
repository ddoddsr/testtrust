<?php

namespace App\Providers;


use App\Models\Company;
use App\Policies\RolePolicy;
use App\Policies\PermissionPolicy;
use App\Policies\CompanyPolicy;
use App\Models\ConnectedAccount;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Gate;
use App\Policies\ConnectedAccountPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Company::class => CompanyPolicy::class,
        ConnectedAccount::class => ConnectedAccountPolicy::class,
        Role::class => RolePolicy::class,
        Permission::class => PermissionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super-Admin') ? true : null;
        });
        //
    }
}
