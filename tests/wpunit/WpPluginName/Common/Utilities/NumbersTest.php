<?php

namespace WpPluginName_Tests_Wpunit\WpPluginName\Common\Utilities;

use Codeception\TestCase\WPTestCase;
use Generator;
use WpPluginName_Tests_Support\WpunitTester;
use WpPluginName\Common\Utilities\Numbers;

class NumbersTest extends WPTestCase {

	/**
	 * @var WpunitTester
	 */
	protected $tester;

	/**
	 * @inheritDoc
	 */
	public function setUp(): void {
		// Before...
		parent::setUp();
	}

	/**
	 * @inheritDoc
	 */
	public function tearDown(): void {
		// Your tear down methods here.

		// Then...
		parent::tearDown();
	}

	/**
	 * @return Numbers
	 */
	private function make_instance() {
		return new Numbers();
	}

	/**
	 * @test
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( Numbers::class, $sut );
	}

	/**
	 * @dataProvider data_round_up_non_zeros()
	 */
	public function test_round_up_non_zeros( $args ) {
		$num = $this->make_instance();

		[ $value, $expect_zero, $expect_one, $expect_two ] = $args;

		$zero = $num->round_up( $value );
		$one  = $num->round_up( $value, 1 );
		$two  = $num->round_up( $value, 2 );

		$this->assertIsInt( $zero );
		$this->assertIsFloat( $one );
		$this->assertIsFloat( $two );

		$this->assertEquals( $expect_zero, $zero );
		$this->assertEquals( $expect_one, $one );
		$this->assertEquals( $expect_two, $two );
	}

	/**
	 * @return Generator
	 */
	public function data_round_up_non_zeros(): Generator {
		yield '-1.68892' => [ [ - 1.68892, - 1, - 1.6, - 1.68 ] ]; // Rounding up negatives is basically truncating.
		yield '1.18892' => [ [ 1.18892, 2, 1.2, 1.19 ] ];
		yield '203,820' => [ [ 203820, 203820, 203820.0, 203820.00 ] ];
		yield '17.13 float' => [ [ 17.13, 18, 17.2, 17.13 ] ];
		yield '17.13 string' => [ [ '17.13', 18, 17.2, 17.13 ] ];
	}

	/**
	 * @dataProvider data_round_up_zeros()
	 */
	public function test_round_up_zeros( $value ) {
		$num = $this->make_instance();

		$zero = $num->round_up( $value );
		$one  = $num->round_up( $value, 1 );
		$two  = $num->round_up( $value, 2 );

		$this->assertEquals( 0, $zero );
		$this->assertEquals( 0, $one );
		$this->assertEquals( 0, $two );
	}

	/**
	 * @return Generator
	 */
	public function data_round_up_zeros(): Generator {
		yield 'empty string' => [ '' ];
		yield 'non-numeric string' => [ 'abc' ];
		yield '0' => [ 0 ];
		yield '0.00 float' => [ 0.00 ];
		yield '0.00 string' => [ '0.00' ];
	}

	/**
	 * @dataProvider data_round_up_next_non_zeros()
	 */
	public function test_round_up_next_non_zeros( $args ) {
		$num = $this->make_instance();

		[ $value, $interval, $expected ] = $args;

		$result = $num->round_up_to_next( $value, $interval );

		$this->assertEquals( $expected, $result );
	}

	/**
	 * @return Generator
	 */
	public function data_round_up_next_non_zeros(): Generator {
		yield '-1.68892 -> 5' => [ [ - 1.68892, 5, 0 ] ];
		yield '1.18892 -> 5' => [ [ 1.18892, 5, 5 ] ];
		yield '203,820 -> 500' => [ [ 203820, 500, 204000 ] ];
		yield '17.13 float -> 15' => [ [ 17.13, 15, 30 ] ];
		yield '17.13 string -> 15' => [ [ '17.13', 15, 30 ] ];
	}

	/**
	 * @dataProvider data_round_up_next_zeros()
	 */
	public function test_round_up_next_zeros( $args ) {
		$num = $this->make_instance();

		[ $value, $interval ] = $args;

		$result = $num->round_up_to_next( $value, $interval );

		$this->assertEquals( 0, $result );
	}

	/**
	 * @return Generator
	 */
	public function data_round_up_next_zeros(): Generator {
		yield 'empty string -> 5' => [ [ '', 5 ] ];
		yield 'non-numeric string -> 15' => [ [ 'abc', 15 ] ];
		yield '0 -> 50' => [ [ 0, 50 ] ];
		yield '0.00 float -> 55' => [ [ 0.00, 55 ] ];
		yield '0.00 string -> 200' => [ [ '0.00', 200 ] ];
	}

}