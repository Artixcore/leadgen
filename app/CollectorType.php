<?php

namespace App;

enum CollectorType: string
{
    case GoogleMaps = 'google_maps';
    case Directory = 'directory';
    case WebsiteScan = 'website_scan';
    case CsvImport = 'csv_import';
    case ApiConnector = 'api_connector';
}
