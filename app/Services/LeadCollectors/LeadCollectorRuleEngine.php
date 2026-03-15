<?php

namespace App\Services\LeadCollectors;

use App\Models\LeadCollector;
use App\Models\LeadCollectorRule;
use App\Models\RawLeadRecord;

class LeadCollectorRuleEngine
{
    /**
     * Apply collector rules to a raw record. Returns score and whether all required rules passed.
     *
     * @return array{score: int, passed: bool}
     */
    public function evaluate(RawLeadRecord $record, LeadCollector $collector): array
    {
        $rules = $collector->rules()->get();
        $score = 0;
        $requiredFailed = false;
        $payload = $record->normalized_payload ?? $record->raw_payload ?? [];

        foreach ($rules as $rule) {
            $matches = $this->ruleMatches($rule, $payload);
            if ($rule->is_required && ! $matches) {
                $requiredFailed = true;
            }
            if ($matches) {
                $score += $rule->score_weight;
            }
        }

        return [
            'score' => $score,
            'passed' => ! $requiredFailed,
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function ruleMatches(LeadCollectorRule $rule, array $payload): bool
    {
        $value = $payload[$rule->rule_key] ?? null;
        $expected = $rule->rule_value;

        return match ($rule->rule_operator) {
            'eq' => (string) $value === (string) $expected,
            'neq' => (string) $value !== (string) $expected,
            'exists' => $value !== null && $value !== '',
            'not_exists' => $value === null || $value === '',
            default => false,
        };
    }
}
