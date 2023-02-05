<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LeaveBalanceProcessed
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
        Log::debug("dispatching LeaveBalanceProcessed event");
        
        $this->userId = $userId;
        $this->companyId = $companyId;
    }
}
