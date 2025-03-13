<?php

namespace App\Providers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\VerifyEmailResponse;
use Laravel\Fortify\Fortify;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->instance(VerifyEmailResponse::class, new class implements VerifyEmailResponse {
            public function toResponse($request)
            {
                return $request->wantsJson()
                    ? new JsonResponse('', 204)
                    : redirect()->intended(Fortify::redirects('email-verification'))->with([
                        'verified' => true,
                    ]);
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
