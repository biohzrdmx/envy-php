<?php

declare(strict_types = 1);

/**
 * Envy
 * Easy to use, general purpose CuRL wrapper
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @version 2.0
 * @license MIT
 */

# Define the absolute path
define( 'BASE_DIR', dirname( dirname(__FILE__) ) );

# Composer initialization
if ( file_exists( BASE_DIR . '/vendor/autoload.php' ) ) {
	include BASE_DIR . '/vendor/autoload.php';
}