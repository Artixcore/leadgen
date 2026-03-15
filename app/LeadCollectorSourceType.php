<?php

namespace App;

enum LeadCollectorSourceType: string
{
    case Scraper = 'scraper';
    case Api = 'api';
    case Import = 'import';
    case Manual = 'manual';
}
