<?php

namespace App\Events;

use App\Models\LeadImportRun;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImportRunFailed
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public LeadImportRun $run
    ) {}
}
