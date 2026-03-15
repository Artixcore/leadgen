<?php

namespace App;

enum LeadCollectorRunStatus: string
{
    case Pending = 'pending';
    case Running = 'running';
    case Completed = 'completed';
    case Failed = 'failed';
    case Partial = 'partial';
}
