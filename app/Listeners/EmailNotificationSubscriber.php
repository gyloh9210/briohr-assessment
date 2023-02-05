<?php

namespace App\Listeners;

use App\Events\PayslipProcessed;
use App\Events\BirthdayProcessed;
use Illuminate\Support\Facades\Log;

class EmailNotificationSubscriber extends BaseNotificationSubscriber
{
    public function handlePayslip($event)
    {
        Log::debug('payslip email event received', ['event' => $event]);

        $this->_sendEmail($event->userId, $event->companyId, $this->notificationService::MONTHLY_PAYSLIP);
    }

    public function handleBirthday($event)
    {
        Log::debug('birthday email event received', ['event' => $event]);

        $this->_sendEmail($event->userId, $event->companyId, $this->notificationService::BIRTHDAY);
    }

    private function _sendEmail(int $userId, int $companyId, string $type)
    {
        $user = $this->userService->getUser($userId);
        $company = $this->companyService->getCompany($companyId);

        if ($company['email_enabled'] && $user['email_enabled']) {
            if ($type === $this->notificationService::MONTHLY_PAYSLIP) {
                $this->mailService->sendEmail(
                    $user['email'],
                    'Please log in and view your payslip.',
                    'Your payslip is ready'
                );
            } else if ($type === $this->notificationService::BIRTHDAY) {
                $this->mailService->sendEmail(
                    $user['email'],
                    $company['name'] . ' is wishing you happy birthday!',
                    'Happy birthday ' . $user['name']
                );
            }
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            PayslipProcessed::class,
            [EmailNotificationSubscriber::class, 'handlePayslip']
        );

        $events->listen(
            BirthdayProcessed::class,
            [EmailNotificationSubscriber::class, 'handleBirthday']
        );
    }
}
