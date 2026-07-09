<?php

namespace Tests\Unit;

use App\Services\SeoService;
use Tests\TestCase;

class SeoServiceTest extends TestCase
{
    public function test_meta_title_is_capped(): void
    {
        $seo = new SeoService;
        $title = $seo->generateMetaTitle('Un nom de produit relativement long pour tester la limite');
        $this->assertLessThanOrEqual(60, mb_strlen($title));
    }
}
