<?php

declare(strict_types = 1);

/**
 * Envy
 * Easy to use, general purpose CuRL wrapper
 * @author 	biohzrdmx <github.com/biohzrdmx>
 * @version 2.0
 * @license MIT
 */

namespace Envy\Tests;

use Exception;
use LogicException;
use RuntimeException;

use PHPUnit\Framework\TestCase;

use Envy\Settings;

class EnvyTest extends TestCase {

	public function testLoadInexistentFile() {
		# Try with non-existent file
		$settings = new Settings();
		$this->expectException(RuntimeException::class);
		$settings->load( __DIR__ . DIRECTORY_SEPARATOR . '.dummy.env' );
	}

	public function testLoadFromEnvFile() {
		# Now load a valid file
		$settings = new Settings();
		$settings->load( __DIR__ . DIRECTORY_SEPARATOR . '.env' );
		$this->assertEquals('Test', $settings->get('TEST'));
		$this->assertEquals('Quoted value as it has spaces and special (áéíóúñ) characters', $settings->get('QUOTED'));
		$this->assertEquals(true, $settings->get('BOOLEAN'));
		$this->assertEquals('This is a Test', $settings->get('WITH_VAR'));
	}

	public function testRequireSettingSimple() {
		# Now load a valid file
		$settings = new Settings();
		$settings->load( __DIR__ . DIRECTORY_SEPARATOR . '.env' );
		try {
			$settings->require('DUMMY');
			throw new LogicException('This must throw a RuntimeException');
		} catch (Exception $e) {
			$this->assertInstanceOf(RuntimeException::class, $e);
		}
	}

	public function testRequireSettingArray() {
		# Now load a valid file
		$settings = new Settings();
		$settings->load( __DIR__ . DIRECTORY_SEPARATOR . '.env' );
		try {
			$settings->require(['FOO', 'BAR']);
			throw new LogicException('This must throw a RuntimeException');
		} catch (Exception $e) {
			$this->assertInstanceOf(RuntimeException::class, $e);
		}
	}

	public function testDefine() {
		# Now load a valid file
		$settings = new Settings();
		$settings->load( __DIR__ . DIRECTORY_SEPARATOR . '.env' );
		$settings->define('WITH_VAR');
		$this->assertEquals('This is a Test', constant('WITH_VAR'));
	}
}
