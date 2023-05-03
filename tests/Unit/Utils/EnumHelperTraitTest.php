<?php

namespace Tests\Unit\Utils;

use App\Enums\PdfService;
use Illuminate\Validation\Rules\In;
use PHPUnit\Framework\TestCase;

class EnumHelperTraitTest extends TestCase
{
    public function testGetValuesReturnsAListOfValuesFromEnum()
    {
        $values = PdfService::getValues();

        $this->assertEquals([
            PdfService::GOTENBERG->value,
            PdfService::WK_HTML_TO_PDF->value,
            PdfService::MPDF->value,
        ], $values);
    }

    public function testGetNamesReturnsAListOfNamesFromEnum()
    {
        $names = PdfService::getNames();

        $this->assertEquals([
            'GOTENBERG',
            'WK_HTML_TO_PDF',
            'MPDF',
        ], $names);
    }

    public function testGetRequestRuleReturnsTheInRuleForLaravelRequest()
    {
        $rule = PdfService::getRequestRule();

        $this->assertInstanceOf(In::class, $rule);
    }
}
