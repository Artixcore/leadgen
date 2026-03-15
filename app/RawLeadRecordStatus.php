<?php

namespace App;

enum RawLeadRecordStatus: string
{
    case Pending = 'pending';
    case Normalized = 'normalized';
    case Filtered = 'filtered';
    case Duplicate = 'duplicate';
    case Accepted = 'accepted';
    case Rejected = 'rejected';
    case Failed = 'failed';
}
