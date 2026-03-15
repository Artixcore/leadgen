<?php

namespace App\Services\LeadSearch;

class LeadOpportunityExplainerService
{
    /**
     * Add explanation and recommended_pitch to each candidate based on opportunity signals.
     *
     * @param  array<int, array<string, mixed>>  $candidates
     * @return array<int, array<string, mixed>>
     */
    public function addExplanations(array $candidates): array
    {
        $rules = config('lead_signal_rules.signals', []);
        $defaultExplanation = config('lead_signal_rules.default_explanation', 'This business matches your search criteria.');
        $defaultPitch = config('lead_signal_rules.default_pitch_hint', 'Introduce your services and offer a free consultation.');

        foreach ($candidates as $i => $candidate) {
            $signals = $candidate['opportunity_signals'] ?? [];
            if (! is_array($signals)) {
                $signals = [];
            }
            $explanations = [];
            $pitchHints = [];
            foreach ($signals as $signalKey) {
                $rule = $rules[$signalKey] ?? null;
                if ($rule) {
                    $explanations[] = $rule['explanation'] ?? $signalKey;
                    if (! empty($rule['pitch_hint'])) {
                        $pitchHints[] = $rule['pitch_hint'];
                    }
                }
            }
            $candidates[$i]['explanation'] = $explanations !== []
                ? implode(' ', $explanations)
                : $defaultExplanation;
            $candidates[$i]['recommended_pitch'] = $pitchHints !== []
                ? implode(' ', array_slice($pitchHints, 0, 2))
                : $defaultPitch;
        }

        return $candidates;
    }
}
