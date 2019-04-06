<?php

namespace Liudian\Admin\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Liudian\Admin\Model\AdminUserOperationLog;

class AdminLog
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $targetClass;

    public $targetId;

    public $operationName;

    public $intro;

    public $data;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($targetClass = '',
                                $targetId = 0,
                                $operationName = '创建',
                                $intro = '',
                                $data = []
    ){
        $this->targetClass = $targetClass;
        $this->targetId = $targetId;
        $this->operationName = $operationName;
        $this->intro = $intro;
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
