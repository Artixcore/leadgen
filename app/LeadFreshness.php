<?php

namespace App;

enum LeadFreshness: string
{
    case Fresh = 'fresh';
    case Stale = 'stale';
    case Unknown = 'unknown';
}
