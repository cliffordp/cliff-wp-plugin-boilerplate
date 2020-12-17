<?php

namespace WpPluginName_Tests_Wpunit\WpPluginName\Common\Utilities;

use Codeception\TestCase\WPTestCase;
use WpPluginName_Tests_Support\WpunitTester;
use WpPluginName\Common\Utilities\Arrays;

class ArraysTest extends WPTestCase {

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
	 * @return Arrays
	 */
	private function make_instance() {
		return new Arrays();
	}

	/**
	 * @test
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( Arrays::class, $sut );
	}

	public function test_flatten_array_from_multi_to_flat() {
		$mix = [
			'a',
			[ 'bla' => 77 ],
			[ 'bla' => 88 ],
			'b',
			[ 5, 6 ],
			[ 0 => 7 ],
			[ [ [ 'x' ], 'y', 'z' ] ],
			[ [ 'p' ] ],
		];

		$arrays = $this->make_instance();

		$result = $arrays->flatten_array( $mix );

		$expected = [
			0     => 'a',
			'bla' => 88,
			1     => 'b',
			2     => 5,
			3     => 6,
			4     => 7,
			5     => 'x',
			6     => 'y',
			7     => 'z',
			8     => 'p',
		];

		$this->assertEqualSets( $expected, $result );
	}

	public function test_flatten_array_flat_should_remain_unchanged() {
		$flat = [
			'a',
			'b' => 7,
			3   => 7,
		];

		$arrays = $this->make_instance();

		$result = $arrays->flatten_array( $flat );

		$this->assertEqualSets( $flat, $result );
	}

	public function test_flatten_array_empty_should_remain_unchanged() {
		$empty = [];

		$arrays = $this->make_instance();

		$result = $arrays->flatten_array( $empty );

		$this->assertEqualSets( $empty, $result );
	}

	public function test_sanitize_multiple_values_array_remain_same() {
		$arrays = $this->make_instance();

		$values = [
			'a' => 'ant',
			'b' => 'bee',
			'c' => 'cat',
		];

		$json = json_encode( $values );

		$allowables = [
			'a',
			'b',
			'c',
		];

		$a = $arrays->sanitize_multiple_values( $values, $allowables );
		$j = $arrays->sanitize_multiple_values( $json, $allowables );

		$this->assertEqualSets( $a, $values );
		$this->assertEquals( $j, $json );
	}

	public function test_sanitize_multiple_values_array_keep_abc_all() {
		$arrays = $this->make_instance();

		$values = [
			'c' => 'cat',
			3   => 'three',
			'a' => 'ant',
			'd' => 'dog',
			'b' => 'bee',
		];

		$json = json_encode( $values );

		$allowables = [
			'a',
			'b',
			'c',
		];

		$expect = [
			'a' => 'ant',
			'b' => 'bee',
			'c' => 'cat',
		];

		$expect_json = json_encode( $expect );

		$a = $arrays->sanitize_multiple_values( $values, $allowables );
		$j = $arrays->sanitize_multiple_values( $json, $allowables );

		$this->assertEqualSets( $expect, $a );
		$this->assertJsonStringEqualsJsonString( $expect_json, $j );
	}

	public function test_sanitize_multiple_values_array_keep_abc_some() {
		$arrays = $this->make_instance();

		$values = [
			'c' => 'cat',
			3   => 'three',
			'b' => 'bee',
			'd' => 'dog',
		];

		$allowables = [
			'a',
			'b',
			'c',
		];

		// In same order as found, since using assertEquals().
		$expect = [
			'c' => 'cat',
			'b' => 'bee',
		];

		$result = $arrays->sanitize_multiple_values( $values, $allowables );

		$this->assertEquals( $expect, $result );
	}

	public function test_sanitize_multiple_values_empty_if_allowables_is_associative() {
		$arrays = $this->make_instance();

		$values = [
			'c' => 'cat',
			3   => 'three',
			'b' => 'bee',
			'd' => 'dog',
		];

		$allowables = [
			'c' => 'cat',
			'b' => 'bee',
		];

		$result = $arrays->sanitize_multiple_values( $values, $allowables );

		$this->assertEquals( [], $result );
	}

	public function test_sanitize_multiple_values_empty_if_values_not_associative() {
		$arrays = $this->make_instance();

		$values = [
			'a',
			'b',
			'c',
		];

		$result = $arrays->sanitize_multiple_values( $values, $values );

		$this->assertEquals( [], $result );
	}

	public function test_sanitize_multiple_values_bool_values_pass_through() {
		$arrays = $this->make_instance();

		$allowables = [
			'a',
			'b',
			'c',
		];

		$true  = $arrays->sanitize_multiple_values( true, $allowables );
		$false = $arrays->sanitize_multiple_values( false, $allowables );

		$this->assertTrue( $true );
		$this->assertFalse( $false );
	}

	public function test_is_associative_array() {
		$arrays = $this->make_instance();

		$array = [
			1        => 'one',
			500      => 'five hundred',
			400      => 'four hundred',
			'string' => 'should be ignored',
			0        => 'zero',
			2        => 'two',
		];

		$result = $arrays->is_associative_array( $array );

		$this->assertEquals( true, $result );
	}

	public function test_is_not_associative_array() {
		$arrays = $this->make_instance();

		$array = [
			1   => 'one',
			500 => 'five hundred',
			400 => 'four hundred',
			0   => 'zero',
			2   => 'two',
		];

		$result = $arrays->is_associative_array( $array );

		$this->assertEquals( false, $result );
	}

	public function test_get_max_int_key() {
		$arrays = $this->make_instance();

		$expected = 500;

		$array = [
			1         => 'one',
			$expected => 'five hundred',
			400       => 'four hundred',
			'string'  => 'should be ignored',
			0         => 'zero',
			2         => 'two',
		];

		$result = $arrays->get_max_int_key( $array );

		$this->assertEquals( $expected, $result );
	}

	public function test_get_max_int_key_empty_should_false() {
		$arrays = $this->make_instance();

		$empty = [];

		$result = $arrays->get_max_int_key( $empty );

		$this->assertFalse( $result );
	}

	public function test_filter_array_numeric_keys_as_int_keys() {
		$arrays = $this->make_instance();

		$array = [
			1        => 'one',
			500      => 'five hundred',
			400      => 'four hundred',
			'string' => 'should be ignored',
			0        => 'zero',
			'7.3'    => 'should be 7',
			'8.8'    => 'nine',
			2        => 'two',
		];

		$expected = [
			1   => 'one',
			500 => 'five hundred',
			400 => 'four hundred',
			0   => 'zero',
			7   => 'should be 7',
			9   => 'nine',
			2   => 'two',
		];

		$result = $arrays->filter_array_only_numeric_keys_as_int( $array );

		$this->assertEqualSets( $expected, $result );
	}

	public function test_no_numeric_keys_should_be_empty() {
		$arrays = $this->make_instance();

		$array = [
			'string' => 'should be ignored',
			'seven'  => 'should be 7',
			'nine'   => 'nine',
		];

		$expected = [];

		$result = $arrays->filter_array_only_numeric_keys_as_int( $array );

		$this->assertEqualSets( $expected, $result );
	}

	public function test_empty_should_stay_empty() {
		$arrays = $this->make_instance();

		$array = [];

		$expected = [];

		$result = $arrays->filter_array_only_numeric_keys_as_int( $array );

		$this->assertEqualSets( $expected, $result );
	}

	public function test_lookup_next_integer_exact_key() {
		$arrays = $this->make_instance();

		$array = [
			1        => 'one',
			500      => 'five hundred',
			400      => 'four hundred',
			'string' => 'should be ignored',
			0        => 'zero',
			'7.3'    => 'seven',
			'8.8'    => 'nine',
			2        => 'two',
		];

		$lookup = 7;

		$result = $arrays->lookup_next_array_integer_key( $array, $lookup );

		$this->assertEquals( 7, $result );
	}

	public function test_lookup_next_integer_exact_value() {
		$arrays = $this->make_instance();

		$array = [
			1        => 'one',
			500      => 'five hundred',
			400      => 'four hundred',
			'string' => 'should be ignored',
			0        => 'zero',
			'7.3'    => 'seven',
			'8.8'    => 'nine',
			2        => 'two',
		];

		$lookup = 7;

		$result = $arrays->lookup_next_array_integer_key( $array, $lookup, true );

		$this->assertEquals( 'seven', $result );
	}

	public function test_lookup_next_integer_key_from_10_to_400() {
		$arrays = $this->make_instance();

		$array = [
			1        => 'one',
			500      => 'five hundred',
			400      => 'four hundred',
			'string' => 'should be ignored',
			0        => 'zero',
			'7.3'    => 'seven',
			'8.8'    => 'nine',
			2        => 'two',
		];

		$lookup = 10;

		$result = $arrays->lookup_next_array_integer_key( $array, $lookup );

		$this->assertEquals( 400, $result );
	}

	public function test_lookup_next_integer_key_from_10_to_400_get_value() {
		$arrays = $this->make_instance();

		$array = [
			1        => 'one',
			500      => 'five hundred',
			400      => 'four hundred',
			'string' => 'should be ignored',
			0        => 'zero',
			'7.3'    => 'seven',
			'8.8'    => 'nine',
			2        => 'two',
		];

		$lookup = 10;

		$result = $arrays->lookup_next_array_integer_key( $array, $lookup, true );

		$this->assertEquals( 'four hundred', $result );
	}

	public function test_lookup_next_integer_key_over_max_should_be_null() {
		$arrays = $this->make_instance();

		$array = [
			1        => 'one',
			500      => 'five hundred',
			400      => 'four hundred',
			'string' => 'should be ignored',
			0        => 'zero',
			'7.3'    => 'seven',
			'8.8'    => 'nine',
			2        => 'two',
		];

		$lookup = 501;

		$null_key   = $arrays->lookup_next_array_integer_key( $array, $lookup );
		$null_value = $arrays->lookup_next_array_integer_key( $array, $lookup, true );

		$this->assertNull( $null_key );
		$this->assertNull( $null_value );
	}

}