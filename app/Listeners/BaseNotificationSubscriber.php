<?php

namespace App\Listeners;

use App\Services\CompanyService;
use App\Services\MailService;
use App\Services\NotificationService;
use App\Services\UserService;

class BaseNotificationSubscriber
{
    protected UserService $userService;
    protected CompanyService $companyService;
    protected NotificationService $notificationService;
    protected MailService $mailService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        UserService $userService, 
        CompanyService $companyService, 
        NotificationService $notificationService,
        MailService $mailService,
    )
    {
        $this->userService = $userService;
        $this->companyService = $companyService;
        $this->notificationService = $notificationService;
        $this->mailService = $mailService;
    }
}
