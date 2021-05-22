# envy-php

Simple ENV file parser

### Basic usage

First require `biohzrdmx/envy-php` with Composer.

Now create a `.env` file, for example:

```dosini
APP_NAME=MyApp
APP_URL=https://app.example.com
APP_DEFAULT="${APP_URL}/dashboard"
APP_DEBUG=true
```

Create now an instance of the `Settings` class:

```php
use Envy\Settings;
$settings = new Settings();
$settings->load( dirname(__FILE__) . '/.env' );
```

If the file can not be loaded it will throw a `RuntimeException`.

Then simply call the `load($name)` function to get the value of the specified option:

```php
$app_name = $settings->get('APP_NAME');
```

You may also define a constant with the same name by using the `define($name)` method:

```php
$settings->define('APP_NAME');
echo APP_NAME; # Prints 'MyApp'
```

Finally, you may test options for existence with the `require($name)` function:

```php
# Testing for a single option
$settings->require('APP_NAME');

# Testing for multiple options
$settings->require([ 'APP_NAME', 'APP_URL' ]);
```

If a required setting is not available it will throw a `RuntimeException`.

### Licensing

This software is released under the MIT license.

Copyright © 2021 biohzrdmx

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

### Credits

**Lead coder:** biohzrdmx [github.com/biohzrdmx](http://github.com/biohzrdmx)