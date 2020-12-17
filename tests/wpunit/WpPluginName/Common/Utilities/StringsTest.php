<?php

namespace WpPluginName_Tests_Wpunit\WpPluginName\Common\Utilities;

use Codeception\TestCase\WPTestCase;
use Stringy\Stringy;
use WpPluginName_Tests_Support\WpunitTester;
use WpPluginName\Common\Utilities\Strings;

class StringsTest extends WPTestCase {

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
	 * @return Strings
	 */
	private function make_instance() {
		return new Strings();
	}

	/**
	 * @test
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( Strings::class, $sut );
	}

	/**
	 * @test
	 */
	public function it_should_instantiate_stringy() {
		$strings = $this->make_instance();

		$stringy                         = $strings->stringy( 'string' );
		$stringy_did_something           = $stringy->removeRight( 'ing' );
		$stringy_did_something_to_string = $stringy_did_something->toString();

		$this->assertInstanceOf( Stringy::class, $stringy );
		$this->assertInstanceOf( Stringy::class, $stringy_did_something );
		$this->assertIsString( Stringy::class, $stringy_did_something_to_string );
	}

	public function test_get_filename_wo_extension() {
		$strings = $this->make_instance();

		$name = 'admin';
		$full = $name . '.css';
		$min  = $name . '.min.css';

		$full_result = $strings->stringy( $full )->removeRight( '.min.css' )->removeRight( '.css' )->toString();
		$min_result  = $strings->stringy( $min )->removeRight( '.min.css' )->removeRight( '.css' )->toString();

		$this->assertEquals( $name, $full_result );
		$this->assertEquals( $full_result, $min_result );
	}

}