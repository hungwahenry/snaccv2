<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateNotificationSettingsRequest;
use App\Models\NotificationChannel;
use App\Models\NotificationType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = $request->user()
            ->notifications()
            ->paginate(20);

        $types = NotificationType::all();
        $channels = NotificationChannel::all();

        return view('notifications.index', compact('notifications', 'types', 'channels'));
    }

    public function markAsRead(Request $request, string $id)
    {
        $notification = $request->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }
    public function updateSettings(UpdateNotificationSettingsRequest $request)
    {
        $validated = $request->validated();

        $request->user()->settings()->updateOrCreate(
            [
                'type' => $validated['type'],
                'channel' => $validated['channel'],
            ],
            [
                'is_enabled' => $validated['enabled'],
            ]
        );

        return response()->json(['success' => true]);
    }
}
