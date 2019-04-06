<?php

namespace Liudian\Admin\Listeners;

use Liudian\Admin\Events\AdminLog;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Liudian\Admin\Model\AdminUserOperationLog;

class WriteAdminOperationLog
{

    protected $operationLog;
    /**
     * Create the event listener.
     * @return void
     */
    public function __construct(AdminUserOperationLog $adminUserOperationLog)
    {
        $this->operationLog = $adminUserOperationLog;
    }

    /**
     * Handle the event.
     * @param  AdminLog  $event
     * @return void
     */
    public function handle(AdminLog $event)
    {
        // 记录日志
        $this->operationLog->create([
            'admin_user_id' => \AdminAuth::user()->id,
            'operation_name' => $event->operationName,
            'target_class' => $event->targetClass,
            'target_id' => $event->targetId,
            'operation_intro' => $event->intro,
            'operation_data' => $event->data ? json_encode($event->data) : ''
        ]);
    }
}
