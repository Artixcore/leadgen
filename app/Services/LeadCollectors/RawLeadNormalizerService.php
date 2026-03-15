<?php

namespace App\Services\LeadCollectors;

use Illuminate\Support\Str;

class RawLeadNormalizerService
{
    /**
     * Normalize raw payload into a standard structure for lead fields.
     *
     * @param  array<string, mixed>  $rawPayload
     * @return array<string, mixed>
     */
    public function normalize(array $rawPayload): array
    {
        return [
            'company_name' => $this->extractString($rawPayload, ['company_name', 'company', 'name', 'business_name']),
            'website' => $this->extractString($rawPayload, ['website', 'url', 'web', 'site']),
            'email' => $this->extractString($rawPayload, ['email', 'email_address', 'e-mail']),
            'phone' => $this->extractString($rawPayload, ['phone', 'telephone', 'mobile', 'phone_number']),
            'address' => $this->extractString($rawPayload, ['address', 'street', 'location']),
            'country' => $this->extractString($rawPayload, ['country', 'country_code']),
            'city' => $this->extractString($rawPayload, ['city', 'town', 'locality']),
            'niche' => $this->extractString($rawPayload, ['niche', 'industry', 'category']),
            'source_url' => $this->extractString($rawPayload, ['source_url', 'url', 'link', 'website']),
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     * @param  array<int, string>  $keys
     */
    private function extractString(array $payload, array $keys): ?string
    {
        foreach ($keys as $key) {
            $v = $payload[$key] ?? null;
            if ($v !== null && $v !== '') {
                $s = is_scalar($v) ? (string) $v : null;
                if ($s !== null && Str::length($s) > 0) {
                    return $s;
                }
            }
        }

        return null;
    }
}
