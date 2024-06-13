<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Report\Report;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Test\Solid\Services\HtmlExporHtmlDocument;
use App\Test\Solid\Services\HtmlExportPdfDocument;

use App\Test\Solid\OCP\Services\StripePaymentMethod;
use App\Test\Solid\Interfaces\ExportableDocumentInterface;
use App\Test\Solid\OCP\Services\Interfaces\PaymentGatewayInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // app()->bind(ExportableDocumentInterface::class, HtmlExporHtmlDocument::class);
        app()->bind(PaymentGatewayInterface::class, StripePaymentMethod::class);


    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('show-report', function (User $user, Report $report) {
            return $user->isAdmin() || $user->id === $report->user_id;
        });

        // Gate::define('update-post', [PostPolicy::class, 'update']);
    }
}
