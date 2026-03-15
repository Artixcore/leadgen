<?php

namespace Tests\Unit;

use App\Services\LeadSearch\DTO\LeadSearchIntentDTO;
use App\Services\LeadSearch\LeadQueryParserService;
use Tests\TestCase;

class LeadQueryParserServiceTest extends TestCase
{
    private LeadQueryParserService $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new LeadQueryParserService;
    }

    public function test_parses_digital_marketing_and_dubai(): void
    {
        $intent = $this->parser->parse('digital marketing leads in Dubai');

        $this->assertInstanceOf(LeadSearchIntentDTO::class, $intent);
        $this->assertSame('digital_marketing', $intent->targetService);
        $this->assertSame('Dubai', $intent->targetCity);
        $this->assertSame('United Arab Emirates', $intent->targetCountry);
    }

    public function test_parses_seo_and_canada(): void
    {
        $intent = $this->parser->parse('small businesses in Canada needing SEO');

        $this->assertSame('seo', $intent->targetService);
        $this->assertSame('Canada', $intent->targetCountry);
        $this->assertSame('small', $intent->companySize);
        $this->assertContains('weak_seo', $intent->opportunitySignals);
    }

    public function test_parses_web_development_restaurants_london(): void
    {
        $intent = $this->parser->parse('web development leads for restaurants in London');

        $this->assertSame('web_development', $intent->targetService);
        $this->assertSame('restaurants', $intent->targetNiche);
        $this->assertSame('London', $intent->targetCity);
        $this->assertSame('United Kingdom', $intent->targetCountry);
    }

    public function test_structured_filters_override_parsed(): void
    {
        $intent = $this->parser->parse('leads in Dubai', [
            'target_service' => 'seo',
            'target_niche' => 'clinics',
        ]);

        $this->assertSame('seo', $intent->targetService);
        $this->assertSame('clinics', $intent->targetNiche);
        $this->assertSame('Dubai', $intent->targetCity);
    }

    public function test_returns_dto_with_defaults_for_gibberish(): void
    {
        $intent = $this->parser->parse('xyz abc 123');

        $this->assertNull($intent->targetService);
        $this->assertNull($intent->targetNiche);
        $this->assertNull($intent->targetCountry);
        $this->assertNull($intent->targetCity);
        $this->assertSame([], $intent->opportunitySignals);
    }
}
