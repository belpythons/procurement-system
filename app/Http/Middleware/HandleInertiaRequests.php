<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template loaded on the first page visit.
     * Overridden per-request in the controller via ->rootView().
     */
    protected $rootView = 'report-app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define shared props available to all Inertia pages.
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? [
                    'id'    => $request->user()->id,
                    'name'  => $request->user()->name,
                    'email' => $request->user()->email,
                ] : null,
            ],
            'flash' => [
                'message'       => fn () => $request->session()->get('flash.message'),
                'import_log_id' => fn () => $request->session()->get('flash.import_log_id'),
                'success'       => fn () => $request->session()->get('success'),
                'error'         => fn () => $request->session()->get('error'),
            ],
        ]);
    }
}
