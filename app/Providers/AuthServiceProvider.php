<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\User;
use Illuminate\Database\Eloquent\Model;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->registerUpdateDeletePolicies();
    }

    public function registerUpdateDeletePolicies() {
        Gate::define('update-resource', function(Model $resource) {
            if(!Auth::check()) return false;
            
            $user = Auth::user();

            if($user->hasRole('admin')) {
                return true;
            }
            
            $relations = $resource->getRelations();
            if(array_key_exists('user', $relations)) {
                return $relations['user']->id === $user->id;
            }

            return false;
        });

        Gate::define('delete-resource', function(Model $resource) {
            if(!Auth::check()) return false;
            
            $user = Auth::user();

            if($user->hasRole('admin')) {
                return true;
            }
            
            $relations = $resource->getRelations();
            if(array_key_exists('user', $relations)) {
                return $relations['user']->id === $user->id;
            }

            return false;
        });
    }
}
