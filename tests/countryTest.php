<?php

use PHPUnit\Framework\TestCase;
use Countries\Countries;

class countryTest extends TestCase
{

    public function testConstruction(): void
    {
        new Countries();
        $this->assertTrue(true);
    }

    public function testAltSpellings(): void
    {
        $altSpellings = include 'src/altSpellings.php';

        foreach ($altSpellings as $alt => $iso)
        {
            $msg = '"%s" alt spelling "%s" has capitalization issues';
            $msg = sprintf($msg, $iso, $alt);

            $this->assertTrue(preg_match('/[A-Z]/', $alt) === 0, $msg);
            $this->assertTrue(preg_match('/^[A-Z]{2}$/', $iso) === 1, $msg);

            $msg = '"%s" alt spelling "%s" has punctuation issues';
            $msg = sprintf($msg, $iso, $alt);

            $this->assertTrue(preg_match('/^[a-z ]+$/', $alt) === 1, $msg);
        }
    }

    public function testGetCountry1(): void
    {
        $countries = new Countries();
        $results   = $countries->getCountry('United States');

        $this->assertEquals('US', $results['iso2']);
    }

    public function testGetCountry2(): void
    {
        $countries = new Countries();

        $results = $countries->getCountry('United States of America');
        $this->assertEquals('US', $results['iso2']);

        $results = $countries->getCountry('UnitedStates');
        $this->assertEquals('US', $results['iso2']);

        $results = $countries->getCountry('Canida');
        $this->assertEquals('CA', $results['iso2']);

        $results = $countries->getCountry('Kyrgyztan');
        $this->assertEquals('KG', $results['iso2']);

        $results = $countries->getCountry('St Maarten');
        $this->assertEquals('SX', $results['iso2']);
    }

    public function testGetCountry3(): void
    {
        $countries = new Countries();

        $results = $countries->getCountry('Vatican');
        $this->assertEquals('VA', $results['iso2']);

        $results = $countries->getCountry('Lao People\'s Democratic Republic');
        $this->assertEquals('LA', $results['iso2']);

    }

    public function testInvalidGetCountry(): void
    {
        $countries = new Countries();

        $this->assertNull($countries->getCountry('Not A Country'));
        $this->assertNull($countries->getCountry(5000));
    }

    public function testGetAllCountries(): void
    {
        $countries = new Countries();
        $results   = $countries->getAllCountries();

        $this->assertCount(250, $results);
    }

    public function testGetAllCountryNames(): void
    {
        $countries = new Countries();
        $results   = $countries->getAllCountryNames();

        $this->assertCount(250, $results);
        $this->assertContains('United States', $results);
    }

    public function testGetCountryFromIso(): void
    {
        $countries    = new Countries();
        $resultString = $countries->getCountryFromISO('840');
        $resultNum    = $countries->getCountryFromISO(840);

        $this->assertEquals('840', $resultString['isoNum']);
        $this->assertEquals('840', $resultNum['isoNum']);
    }

    public function testGetCountryFromIsoFail1(): void
    {
        $countries = new Countries();

        $this->expectException(Exception::class);

        $countries->getCountryFromISO('TOO_Long');
    }

    public function testGetCountryFromIsoFail2(): void
    {
        $countries = new Countries();

        $this->expectException(Exception::class);

        $countries->getCountryFromISO(9999);
    }

	public function testSortOrder(): void
	{
		$countries = new Countries();
		$countries->setSort('capital');
		$results = $countries->getAllCountries();

		$this->assertEquals('AE', $results[1]['iso2']);
	}

	public function testBadSortOrder(): void
	{
		$this->expectException(Exception::class);
		$countries = new Countries();
		$countries->setSort('bad');
	}

	public function testValidation(): void
	{
		$countries = new Countries();

		$this->assertTrue($countries->validate('US', 'United States'));
		$this->assertTrue($countries->validate('USA', 'United States'));
		$this->assertFalse($countries->validate('US', 'Canada'));
		$this->assertFalse($countries->validate('', ''));
		$this->assertTrue($countries->validate('SX', 'Sint Maarten'));
	}

	public function testIsValid(): void
	{
		$countries = new Countries();

		$this->assertTrue($countries->valid('United States'));
		$this->assertFalse($countries->valid('United Front of Alien Planets'));
	}

	public function testAssertion(): void
	{
		$countries = new Countries();

		$this->assertTrue($countries->assert('USA', 'United States'));
	}

	public function testAssertionFail(): void
	{
		$this->expectException(Exception::class);

		$countries = new Countries();
		$countries->assert('ZZ', 'United States');
	}

	public function testAssertValid(): void
	{
		$countries = new Countries();

		$this->assertTrue($countries->assertValid('United States'));
	}

	public function testAssertValidFail(): void
	{
		$this->expectException(Exception::class);

		$countries = new Countries();
		$countries->assertValid('Not A Country (yet)');
	}

	public function testAssertException(): void
	{
		$this->expectException(Exception::class);

		$countries = new Countries();
		$countries->assert('TOO_LONG', 'United States');
	}

	public function testUSTerritories(): void
	{
		$countries = new Countries();

		$this->assertTrue($countries->isUSTerritory('Guam'));
		$this->assertTrue($countries->isUSTerritory('Northern Mariana Islands'));
		$this->assertTrue($countries->isUSTerritory('Puerto Rico'));
		$this->assertTrue($countries->isUSTerritory('US Virgin Islands'));
		$this->assertTrue($countries->isUSTerritory('Micronesia'));
		$this->assertTrue($countries->isUSTerritory('Northern Mariana Islands'));
		$this->assertTrue($countries->isUSTerritory('Palau'));
		$this->assertTrue($countries->isUSTerritory('American Samoa'));
		$this->assertTrue($countries->isUSTerritory('PR'));
		$this->assertFalse($countries->isUSTerritory('Canada'));
		$this->assertFalse($countries->isUSTerritory(''));
	}

	public function testLevenshteinMathOnInvalidIso(): void
	{
		$countries = new Countries();

		$this->assertNull($countries->getCountry('ZZ'));
		$this->assertNull($countries->getCountry('ZZZ'));
	}
}