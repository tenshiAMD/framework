<?php

namespace Illuminate\Tests\Support;

use Illuminate\Support\Number;
use PHPUnit\Framework\TestCase;

class SupportNumberTest extends TestCase
{
    public function testFormat()
    {
        $this->needsIntlExtension();

        $this->assertSame('0', Number::format(0));
        $this->assertSame('1', Number::format(1));
        $this->assertSame('10', Number::format(10));
        $this->assertSame('25', Number::format(25));
        $this->assertSame('100', Number::format(100));
        $this->assertSame('100,000', Number::format(100000));
        $this->assertSame('123,456,789', Number::format(123456789));

        $this->assertSame('-1', Number::format(-1));
        $this->assertSame('-10', Number::format(-10));
        $this->assertSame('-25', Number::format(-25));

        $this->assertSame('0.2', Number::format(0.2));
        $this->assertSame('1.23', Number::format(1.23));
        $this->assertSame('-1.23', Number::format(-1.23));
        $this->assertSame('123.456', Number::format(123.456));

        $this->assertSame('∞', Number::format(INF));
        $this->assertSame('NaN', Number::format(NAN));
    }

    public function testFormatWithDifferentLocale()
    {
        $this->needsIntlExtension();

        $this->assertSame('123,456,789', Number::format(123456789, 'en'));
        $this->assertSame('123.456.789', Number::format(123456789, 'de'));
        $this->assertSame('123 456 789', Number::format(123456789, 'fr'));
        $this->assertSame('123 456 789', Number::format(123456789, 'ru'));
        $this->assertSame('123 456 789', Number::format(123456789, 'sv'));
    }

    public function testFormatWithAppLocale()
    {
        $this->needsIntlExtension();

        $this->assertSame('123,456,789', Number::format(123456789));

        Number::useLocale('de');

        $this->assertSame('123.456.789', Number::format(123456789));

        Number::useLocale('en');
    }

    public function testToPercent()
    {
        $this->needsIntlExtension();

        $this->assertSame('0%', Number::toPercentage(0, precision: 0));
        $this->assertSame('0%', Number::toPercentage(0));
        $this->assertSame('1%', Number::toPercentage(1));
        $this->assertSame('10.00%', Number::toPercentage(10, precision: 2));
        $this->assertSame('100%', Number::toPercentage(100));
        $this->assertSame('100.00%', Number::toPercentage(100, precision: 2));

        $this->assertSame('300%', Number::toPercentage(300));
        $this->assertSame('1,000%', Number::toPercentage(1000));

        $this->assertSame('2%', Number::toPercentage(1.75));
        $this->assertSame('1.75%', Number::toPercentage(1.75, precision: 2));
        $this->assertSame('1.750%', Number::toPercentage(1.75, precision: 3));
        $this->assertSame('0%', Number::toPercentage(0.12345));
        $this->assertSame('0.12%', Number::toPercentage(0.12345, precision: 2));
        $this->assertSame('0.1235%', Number::toPercentage(0.12345, precision: 4));
    }

    public function testToCurrency()
    {
        $this->needsIntlExtension();

        $this->assertSame('$0.00', Number::toCurrency(0));
        $this->assertSame('$1.00', Number::toCurrency(1));
        $this->assertSame('$10.00', Number::toCurrency(10));

        $this->assertSame('€0.00', Number::toCurrency(0, 'EUR'));
        $this->assertSame('€1.00', Number::toCurrency(1, 'EUR'));
        $this->assertSame('€10.00', Number::toCurrency(10, 'EUR'));

        $this->assertSame('-$5.00', Number::toCurrency(-5));
        $this->assertSame('$5.00', Number::toCurrency(5.00));
        $this->assertSame('$5.32', Number::toCurrency(5.325));
    }

    public function testToCurrencyWithDifferentLocale()
    {
        $this->needsIntlExtension();

        $this->assertSame('1,00 €', Number::toCurrency(1, 'EUR', 'de'));
        $this->assertSame('1,00 $', Number::toCurrency(1, 'USD', 'de'));
        $this->assertSame('1,00 £', Number::toCurrency(1, 'GBP', 'de'));

        $this->assertSame('123.456.789,12 $', Number::toCurrency(123456789.12345, 'USD', 'de'));
        $this->assertSame('123.456.789,12 €', Number::toCurrency(123456789.12345, 'EUR', 'de'));
        $this->assertSame('1 234,56 $US', Number::toCurrency(1234.56, 'USD', 'fr'));
    }

    public function testBytesToHuman()
    {
        $this->assertSame('0 B', Number::toFileSize(0));
        $this->assertSame('1 B', Number::toFileSize(1));
        $this->assertSame('1 KB', Number::toFileSize(1024));
        $this->assertSame('2 KB', Number::toFileSize(2048));
        $this->assertSame('2.00 KB', Number::toFileSize(2048, precision: 2));
        $this->assertSame('1.23 KB', Number::toFileSize(1264, precision: 2));
        $this->assertSame('1.234 KB', Number::toFileSize(1264, 3));
        $this->assertSame('5 GB', Number::toFileSize(1024 * 1024 * 1024 * 5));
        $this->assertSame('10 TB', Number::toFileSize((1024 ** 4) * 10));
        $this->assertSame('10 PB', Number::toFileSize((1024 ** 5) * 10));
        $this->assertSame('1 ZB', Number::toFileSize(1024 ** 7));
        $this->assertSame('1 YB', Number::toFileSize(1024 ** 8));
        $this->assertSame('1,024 YB', Number::toFileSize(1024 ** 9));
    }

    public function testToHuman()
    {
        $this->assertSame('1', Number::forHumans(1));
        $this->assertSame('10', Number::forHumans(10));
        $this->assertSame('100', Number::forHumans(100));
        $this->assertSame('1 thousand', Number::forHumans(1000));
        $this->assertSame('1 million', Number::forHumans(1000000));
        $this->assertSame('1 billion', Number::forHumans(1000000000));
        $this->assertSame('1 trillion', Number::forHumans(1000000000000));
        $this->assertSame('1 quadrillion', Number::forHumans(1000000000000000));
        $this->assertSame('1 thousand quadrillion', Number::forHumans(1000000000000000000));

        $this->assertSame('123', Number::forHumans(123));
        $this->assertSame('1 thousand', Number::forHumans(1234));
        $this->assertSame('1.23 thousand', Number::forHumans(1234, precision: 2));
        $this->assertSame('12 thousand', Number::forHumans(12345));
        $this->assertSame('1 million', Number::forHumans(1234567));
        $this->assertSame('1 billion', Number::forHumans(1234567890));
        $this->assertSame('1 trillion', Number::forHumans(1234567890123));
        $this->assertSame('1.23 trillion', Number::forHumans(1234567890123, precision: 2));
        $this->assertSame('1 quadrillion', Number::forHumans(1234567890123456));
        $this->assertSame('1.23 thousand quadrillion', Number::forHumans(1234567890123456789, precision: 2));
        $this->assertSame('490 thousand', Number::forHumans(489939));
        $this->assertSame('489.9390 thousand', Number::forHumans(489939, precision: 4));
        $this->assertSame('500.00000 million', Number::forHumans(500000000, precision: 5));

        $this->assertSame('1 million quadrillion', Number::forHumans(1000000000000000000000));
        $this->assertSame('1 billion quadrillion', Number::forHumans(1000000000000000000000000));
        $this->assertSame('1 trillion quadrillion', Number::forHumans(1000000000000000000000000000));
        $this->assertSame('1 quadrillion quadrillion', Number::forHumans(1000000000000000000000000000000));
        $this->assertSame('1 thousand quadrillion quadrillion', Number::forHumans(1000000000000000000000000000000000));

        $this->assertSame('0', Number::forHumans(0));
        $this->assertSame('-1', Number::forHumans(-1));
        $this->assertSame('-10', Number::forHumans(-10));
        $this->assertSame('-100', Number::forHumans(-100));
        $this->assertSame('-1 thousand', Number::forHumans(-1000));
        $this->assertSame('-1 million', Number::forHumans(-1000000));
        $this->assertSame('-1 billion', Number::forHumans(-1000000000));
        $this->assertSame('-1 trillion', Number::forHumans(-1000000000000));
        $this->assertSame('-1 quadrillion', Number::forHumans(-1000000000000000));
        $this->assertSame('-1 thousand quadrillion', Number::forHumans(-1000000000000000000));
    }

    protected function needsIntlExtension()
    {
        if (! extension_loaded('intl')) {
            $this->markTestSkipped('The intl extension is not installed. Please install the extension to enable '.__CLASS__);
        }
    }
}
