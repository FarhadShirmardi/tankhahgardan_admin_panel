<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\PanelUser;
use App\Models\PremiumPlan;
use App\Models\Ticket;
use App\Models\UserReport;
use App\Policies\PanelUserPolicy;
use App\Policies\PremiumPlanPolicy;
use App\Policies\TicketPolicy;
use App\Policies\UserReportPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Ticket::class => TicketPolicy::class,
        PanelUser::class => PanelUserPolicy::class,
        UserReport::class => UserReportPolicy::class,
        PremiumPlan::class => PremiumPlanPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
