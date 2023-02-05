<?php

namespace Tests\Feature\Events;

use Mockery;
use Tests\TestCase;
use App\Services\UserService;
use App\Services\CompanyService;
use Illuminate\Support\Facades\App;
use App\Events\LeaveBalanceProcessed;
use App\Services\MailService;
use App\Services\NotificationService;

class LeaveBalanceProcessedTest extends TestCase
{
    public function test_ui_notification_created_only()
    {
        $userServiceMock = Mockery::mock(UserService::class);
        $companyServiceMock = Mockery::mock(CompanyService::class);
        $notificationServiceMock = Mockery::mock(NotificationService::class);
        $mailServiceMock = Mockery::mock(MailService::class);

        App::instance(UserService::class, $userServiceMock);
        App::instance(CompanyService::class, $companyServiceMock);
        App::instance(NotificationService::class, $notificationServiceMock);
        App::instance(MailService::class, $mailServiceMock);

        $userServiceMock->shouldReceive('getUser')
            ->andReturn([
                "id" => 1011,
                "name" => "test user",
                "email" => "testUser@twitter.com",
                "ui_enabled" => true,
                "email_enabled" => true,
                "company_id" => 101
            ]);

        $companyServiceMock->shouldReceive('getCompany')
            ->andReturn([
                "id" => 102,
                "name" => "twitter",
                "ui_enabled" => true,
                "email_enabled" => true
            ]);;

        $notificationServiceMock->shouldReceive('createNotification')
            ->andReturn(null);

        $mailServiceMock->shouldReceive('sendEmail')
            ->andReturn(null);

        // Execute the event
        LeaveBalanceProcessed::dispatch(1011, 102);

        // Expected outputs
        $notificationServiceMock->shouldHaveReceived('createNotification')
            ->with(1011, 'Please clear your leave before due.')->once();

        $mailServiceMock->shouldNotHaveReceived('sendEmail');
    }

    public function test_nothing_happened()
    {
        $userServiceMock = Mockery::mock(UserService::class);
        $companyServiceMock = Mockery::mock(CompanyService::class);
        $notificationServiceMock = Mockery::mock(NotificationService::class);
        $mailServiceMock = Mockery::mock(MailService::class);

        App::instance(UserService::class, $userServiceMock);
        App::instance(CompanyService::class, $companyServiceMock);
        App::instance(NotificationService::class, $notificationServiceMock);
        App::instance(MailService::class, $mailServiceMock);

        $userServiceMock->shouldReceive('getUser')
            ->andReturn([
                "id" => 1011,
                "name" => "test user",
                "email" => "testUser@facebook.com",
                "ui_enabled" => false,
                "email_enabled" => true,
                "company_id" => 101
            ]);

        $companyServiceMock->shouldReceive('getCompany')
            ->andReturn([
                "id" => 102,
                "name" => "facebook",
                "ui_enabled" => true,
                "email_enabled" => true
            ]);;

        $notificationServiceMock->shouldReceive('createNotification')
            ->andReturn(null);

        $mailServiceMock->shouldReceive('sendEmail')
            ->andReturn(null);

        // Execute the event
        LeaveBalanceProcessed::dispatch(1011, 102);

        // Expected outputs
        $notificationServiceMock->shouldNotHaveReceived('createNotification');

        $mailServiceMock->shouldNotHaveReceived('sendEmail');
    
    }
}
