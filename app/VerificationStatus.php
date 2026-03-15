<?php

namespace App;

enum VerificationStatus: string
{
    case Pending = 'pending';
    case Verified = 'verified';
    case Invalid = 'invalid';
}
