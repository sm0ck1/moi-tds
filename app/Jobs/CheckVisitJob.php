<?php

namespace App\Jobs;

use App\Events\VisitUserEvent;
use App\Models\VisitUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CheckVisitJob implements ShouldQueue
{
    use Queueable;

    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $visitUser = VisitUser::where('uniq_user_hash', $this->data['uniq_user_hash'])->first();
        if (! $visitUser) {
            return;
        }

        $visitUser->update([
            'metrics' => $this->data['metrics'],
        ]);

        event(new VisitUserEvent('User check visit'));
    }
}
