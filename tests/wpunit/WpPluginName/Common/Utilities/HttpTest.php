<?php

namespace WpPluginName_Tests_Wpunit\WpPluginName\Common\Utilities;

use Codeception\TestCase\WPTestCase;
use WpPluginName_Tests_Support\WpunitTester;
use WpPluginName\Common\Utilities\Http;

class HttpTest extends WPTestCase {

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
	 * @return Http
	 */
	private function make_instance() {
		return new Http();
	}

	/**
	 * @test
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( Http::class, $sut );
	}

}