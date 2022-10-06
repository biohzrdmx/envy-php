<?php

declare(strict_types = 1);

/**
 * Envy
 * Easy to use, general purpose CuRL wrapper
 * @author 	biohzrdmx <github.com/biohzrdmx>
 * @version 2.0
 * @license MIT
 */

namespace Envy;

use RuntimeException;

class Settings {

	/**
	 * Load settings from the .env file
	 * @param  string $path Settings path
	 * @return $this
	 */
	public function load(string $path) {
		if (!is_readable($path)) {
			$info = (object) pathinfo($path);
			throw new RuntimeException( sprintf("Load error: '%s' file in folder '%s' is not readable", $info->basename, $info->dirname) );
		}
		$lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$pattern = '/(?:^|\A)\s*([\w\.]+)(?:\s*=\s*?|:\s+?)(\s*\'(?:\\\'|[^\'])*\'|\s*"(?:\"|[^"])*"|[^\#\r\n]+)?\s*(?:\#.*)?(?:$|\z)/';
		if ($lines) {
			foreach ($lines as $line) {
				if (strpos(trim($line), '#') === 0) {
					continue;
				}
				if ( preg_match($pattern, $line, $matches) === 1 ) {
					$name = trim( isset( $matches[1] ) ? $matches[1] : '' );
					$value = trim( isset( $matches[2] ) ? $matches[2] : '' );
					$value = $this->process($value);
					if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
						putenv(sprintf('%s=%s', $name, $value));
						$_ENV[$name] = $value;
						$_SERVER[$name] = $value;
					}
				}
			}
		}
		return $this;
	}

	/**
	 * Get a configuration value
	 * @param  string $name    Value name
	 * @param  mixed  $default Default value
	 * @return mixed
	 */
	public function get(string $name, $default = null) {
		$ret = isset( $_ENV[$name] ) ? $_ENV[$name] : $default;
		if ($ret === 'true' || $ret === 'false') {
			$ret = $ret === 'true';
		}
		return $ret;
	}

	/**
	 * Define a constant based on the value of a given setting
	 * @param  string $name    Setting name
	 * @param  string $default Default value
	 * @return void
	 */
	public function define(string $name, string $default = ''): void {
		$value = $this->get($name, $default);
		if ($value) {
			define($name, $value);
		}
	}

	/**
	 * Require that a setting is available
	 * @param mixed $name Setting name or array of names
	 * @return void
	 */
	public function require($name): void {
		$missing = [];
		$available = true;
		if ( is_array($name) ) {
			foreach ($name as $item) {
				if ( !$this->get($item) ) {
					$available = false;
					$missing[] = $item;
				}
			}
		} else {
			$available = !!$this->get($name);
			$missing[] = $name;
		}
		if (! $available ) {
			throw new RuntimeException( sprintf( "Required settings are missing: %s",  implode(', ', $missing) ) );
		}
	}

	/**
	 * Process a configuration value
	 * @param  string $value Raw configuration value
	 * @return string
	 */
	public function process(string $value): string {
		$ret = $value;
		if ( preg_match('/^(["\'])([^"\']+)\1$/', $value, $matches) === 1 ) {
			$ret = $matches[2];
			if ( $matches[1] == '"' ) {
				# Unescape characters
				$ret = str_replace('\n', "\n", $ret);
				$ret = str_replace('\r', "\r", $ret);
				$ret = preg_replace('/\\\([^$])/', '$1', $ret);
			}
			if ( $matches[1] != "'" ) {
				# Expand $VAR values
				$ret = preg_replace_callback('/(\\\)?(\$)(?!\()\{?([A-Z0-9_]+)?\}?/', function($matches) {
					return isset( $matches[3] ) ? $this->get( $matches[3] ) : '';
				}, $ret);
			}
		}
		return $ret;
	}
}
