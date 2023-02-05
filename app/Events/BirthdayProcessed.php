<?php

namespace App\Events;

use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class BirthdayProcessed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $userId;

    public int $companyId;
        
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $userId, int $companyId)
    {
        Log::debug("dispatching BirthdayProcessed event");

        $this->userId = $userId;
        $this->companyId = $companyId;
    }
}
