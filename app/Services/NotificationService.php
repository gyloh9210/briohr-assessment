<?php

namespace App\Services;

use App\Events\BirthdayProcessed;
use App\Events\LeaveBalanceProcessed;
use App\Events\PayslipProcessed;
use App\Models\Notification;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class NotificationService
{
    const LEAVE_BALANCE = 'leave-balance-reminder';
    const MONTHLY_PAYSLIP = 'monthly-payslip';
    const BIRTHDAY = 'happy-birthday';

    public function getNotificationsByUser(int $userId)
    {
        return Notification::where('user_id', $userId)->get();
    }

    public function sendNotifications(string $type, int $userId, int $companyId)
    {
        switch($type) {
            case self::LEAVE_BALANCE:
                LeaveBalanceProcessed::dispatch($userId, $companyId);
                break;

            case self::MONTHLY_PAYSLIP:
                PayslipProcessed::dispatch($userId, $companyId);
                break;

            case self::BIRTHDAY:
                BirthdayProcessed::dispatch($userId, $companyId);
                break;

            default:
                throw new BadRequestException('invalid notification type');
        }
    }

    public function createNotification(int $userId, string $message)
    {
        Notification::create([
            'user_id' => $userId,
            'message' => $message,
        ]);
    }
}
