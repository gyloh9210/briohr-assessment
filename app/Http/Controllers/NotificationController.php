<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use App\Http\Requests\StoreNotificationRequest;

class NotificationController extends Controller
{
    private NotificationService $_notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->_notificationService = $notificationService;
    }

    public function store(StoreNotificationRequest $request)
    {
        $this->_notificationService
            ->sendNotifications($request->type, $request->user_id, $request->company_id);

        return "ok";
    }

    public function show($userId)
    {
        return $this->_notificationService->getNotificationsByUser($userId);
    }
}
