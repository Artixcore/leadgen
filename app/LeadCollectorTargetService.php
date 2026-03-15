<?php

namespace App;

enum LeadCollectorTargetService: string
{
    case WebDevelopment = 'web_development';
    case DigitalMarketing = 'digital_marketing';
    case Seo = 'seo';
    case SocialMedia = 'social_media';
}
