<?php

use PHPUnit\Framework\TestCase;
use Countries\US;

class usTest extends TestCase {

	public function testConstruction(): void
	{
		new US();
		$this->assertTrue(true);
	}

	public function testExceptionConstruction(): void
	{
		$this->expectException(Exception::class);
		new US('fail - string');
	}

	public function testExceptionConstruction2(): void
	{
		$this->expectException(Exception::class);
		new US(['fail', 'array']);
	}

	public function testOCONUS1(): void
	{
		$stateTest = new US();

		// we return null if a state isn't found
		$this->assertTrue(false === $stateTest->isCONUS('Alaska'));
		$this->assertTrue(false === $stateTest->isCONUS('AK'));
	}

	public function testOCONUS2(): void
	{
		$stateTest = new US();

		$this->assertTrue(false === $stateTest->isCONUS('Hawaii'));
		$this->assertTrue(false === $stateTest->isCONUS('HI'));
	}

	public function testCONUS(): void
	{
		$stateTest = new US();

		$this->assertTrue($stateTest->isCONUS('Alabama'));
		$this->assertTrue($stateTest->isCONUS('AL'));
	}

	public function testBadCONUS(): void
	{
		$stateTest = new US();

		$this->assertTrue(null === $stateTest->isCONUS('Bad'));
	}

	public function testGetAllStates(): void
	{
		$states = new US();

		$this->assertGreaterThan(50, $states->getAllStates());
	}

	public function testTypicalStates(): void
	{
		$stateTest = new US();

		// 50 States
		//  1 Washington DC (district)
		//  3 US Armed Forces Abbreviations
		$this->assertCount(54, $stateTest->getTypicalStates());
	}

	public function getInvalidState(): void
	{
		$stateTest = new US();

		$this->assertNull($stateTest->getState(123));
		$this->assertNull($stateTest->getState('0'));
	}
}