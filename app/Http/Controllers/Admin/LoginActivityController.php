<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginActivity;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LoginActivityController extends Controller
{
    public function index(): View
    {
        $loginActivities = LoginActivity::query()
            ->latest('logged_in_at')
            ->paginate(25, ['*'], 'history_page')
            ->withQueryString();

        $activeSessionTrackingAvailable = config('session.driver') === 'database';
        $activeSessions = null;

        if ($activeSessionTrackingAvailable) {
            $sessionTable = config('session.table', 'sessions');

            $activeSessions = DB::table($sessionTable.' as active_sessions')
                ->join('users', 'active_sessions.user_id', '=', 'users.id')
                ->whereNotNull('active_sessions.user_id')
                ->where(
                    'active_sessions.last_activity',
                    '>=',
                    now()->subMinutes((int) config('session.lifetime'))->timestamp
                )
                ->orderByDesc('active_sessions.last_activity')
                ->paginate(25, [
                    'users.name as name',
                    'users.email as email',
                    'active_sessions.last_activity as last_activity',
                ], 'active_page')
                ->withQueryString();
        }

        return view('admin.login-activity.index', compact(
            'loginActivities',
            'activeSessions',
            'activeSessionTrackingAvailable',
        ));
    }
}
