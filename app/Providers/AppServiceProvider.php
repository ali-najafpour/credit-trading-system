<?php

namespace App\Providers;

use App\Models\User;
use App\Services\Cert;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use App\Services\SmsProvider\SmsIrVerify;
use App\Services\SmsProvider\SmsProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Relation::enforceMorphMap(config('morphmap'));

        $this->app->bind('Cert', Cert::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Schema::defaultStringLength(191);

        // *** https ***
        if(env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }


        // *** rate limiter ***
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });


        // *** user_unique_cell_number ***
        Validator::extend('user_unique_cell_number', function ($attribute, $value) {
            $value = ltrim($value,"0");
            if(Cache::get('new_user_with_mobile__' . $value)){
                return false;
            }elseif(User::where('cell_number', $value)->whereNotNull('cell_number_verified_at')->exists()){
                return false;
            }else{
                return true;
            }
        }, trans('validation.unique'));


        // *** poly_exists ***
        Validator::extend('poly_exists', function ($attribute, $value, $parameters, $validator) {
            // usage:
            // 'commentable_id' => 'required|poly_exists:commentable_type

            if (!$type = Arr::get($validator->getData(), $parameters[0], false)) {
                return false;
            }

            if (Relation::getMorphedModel($type)) {
                $type = Relation::getMorphedModel($type);
            }

            if (!class_exists($type)) {
                return false;
            }

            return !empty(resolve($type)->find($value));
        } , trans('messages.record_not_found'));


        // *** paginator for collections ***
        /**
         * Paginate a standard Laravel Collection.
         *
         * @param int $perPage
         * @param int $total
         * @param int $page
         * @param string $pageName
         * @return array
         */
        Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });

    }
}
