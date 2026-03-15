<?php

return [
    'signals' => [
        'outdated_website' => [
            'label' => 'Outdated website',
            'explanation' => 'Website appears outdated and could benefit from a modern redesign.',
            'pitch_hint' => 'Offer a free website audit and modern redesign proposal.',
            'score_impact' => 15,
        ],
        'weak_seo' => [
            'label' => 'Weak SEO',
            'explanation' => 'Low visibility in search results; SEO improvements could drive more traffic.',
            'pitch_hint' => 'Highlight local SEO and technical SEO improvements.',
            'score_impact' => 20,
        ],
        'no_website' => [
            'label' => 'No website',
            'explanation' => 'Business has no website; clear opportunity for web presence.',
            'pitch_hint' => 'Offer a simple, mobile-friendly starter website.',
            'score_impact' => 25,
        ],
        'weak_social_presence' => [
            'label' => 'Weak social presence',
            'explanation' => 'Limited or inactive social media presence.',
            'pitch_hint' => 'Offer social media strategy and content management.',
            'score_impact' => 12,
        ],
        'poor_mobile_ux' => [
            'label' => 'Poor mobile experience',
            'explanation' => 'Website may not be optimized for mobile devices.',
            'pitch_hint' => 'Offer mobile-first redesign or responsive fixes.',
            'score_impact' => 14,
        ],
        'no_ssl' => [
            'label' => 'No SSL / insecure',
            'explanation' => 'Website does not use HTTPS; security and trust concern.',
            'pitch_hint' => 'Offer SSL setup and security best practices.',
            'score_impact' => 10,
        ],
        'weak_online_presence' => [
            'label' => 'Weak online presence',
            'explanation' => 'Incomplete or minimal online presence across channels.',
            'pitch_hint' => 'Offer a full digital presence audit and roadmap.',
            'score_impact' => 18,
        ],
        'web_opportunity' => [
            'label' => 'Web development opportunity',
            'explanation' => 'Business could benefit from a new or improved website.',
            'pitch_hint' => 'Offer discovery call and tailored proposal.',
            'score_impact' => 15,
        ],
    ],

    'default_explanation' => 'This business matches your search criteria and may benefit from your services.',
    'default_pitch_hint' => 'Introduce your services and offer a free consultation or audit.',
];
