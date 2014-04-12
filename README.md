# Gooseberry

Gooseberry is a simple utility to make http calls. It's designed to be
used for easily accessing simple APIs.

# Usage

You're best bet is to check the tests/examples to see usage, as I'm awful at
documentation.

# Installation

Install the [Packagist archive](https://packagist.org/packages/henderjon/gooseberry)
using [Composer](http://getcomposer.org/). I will *generally* respect
[Semantic Versioning](http://semver.org/). Learn about how Composer
does [versions](https://getcomposer.org/doc/01-basic-usage.md#package-versions).

*Note the absense of v1.0*

```
{
	"require" : {
		"henderjon/gooseberry": "dev-master"
	}
}
```

# License

See LICENSE.md for the [BSD-3-Clause](http://opensource.org/licenses/BSD-3-Clause) license.

# TODO

I'd like to implement a way for users to add various auth methods either by trait or
by lambda. This would allow Gooseberry's use with APIs that use more complex authentication.

Right now there is no recovery from error (either cURL or HTTP). This is by design, however
there are APIs that are simply so unreliable that having a retry loop or a sleep() call
wouldn't hurt. I need to make some decisions in this regard.

I think file uploads, while not necessary for my purposes, would be useful--but that
would end up being ALOT of code for something I don't do very often.
[See example](http://www.daniweb.com/web-development/php/threads/370401/multiple-file-upload-problem-using-stream_context_create-function).

[![Build Status](https://travis-ci.org/henderjon/gooseberry.svg?branch=master)](https://travis-ci.org/henderjon/gooseberry)



