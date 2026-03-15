<?php

namespace App;

enum LeadSourceType: string
{
    case Api = 'api';
    case Manual = 'manual';
    case Import = 'import';
    case Scraper = 'scraper';
    case Partner = 'partner';
}
