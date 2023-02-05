<?php

namespace App\Listeners;

use App\Events\BirthdayProcessed;
use App\Events\LeaveBalanceProcessed;
use Illuminate\Support\Facades\Log;

class UiNotificationSubscriber extends BaseNotificationSubscriber
{
    public function handleBirthday($event)
    {
        Log::debug('birthday ui event received', ['event' => $event]);

        $this->_createNotification(
            $event->userId,
            $event->companyId,
            $this->notificationService::BIRTHDAY
        );
    }

    public function handleLeaveBalance($event)
    {
        Log::debug('leave balance ui event received', ['event' => $event]);

        $this->_createNotification(
            $event->userId,
            $event->companyId,
            $this->notificationService::LEAVE_BALANCE
        );
    }

    private function _createNotification(int $userId, int $companyId, string $type)
    {
        $user = $this->userService->getUser($userId);
        $company = $this->companyService->getCompany($companyId);

        if ($company['ui_enabled'] && $user['ui_enabled']) {
            if ($type === $this->notificationService::LEAVE_BALANCE) {
                $this->notificationService
                    ->createNotification($user['id'], 'Please clear your leave before due.');
            } else if ($type === $this->notificationService::BIRTHDAY) {
                $this->notificationService
                    ->createNotification($user['id'],  'Happy birthday to ' . $user['name']);
            }
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            BirthdayProcessed::class,
            [UiNotificationSubscriber::class, 'handleBirthday']
        );

        $events->listen(
            LeaveBalanceProcessed::class,
            [UiNotificationSubscriber::class, 'handleLeaveBalance']
        );
    }
}
