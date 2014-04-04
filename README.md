# Gooseberry

Gooseberry is a simple wrapper for PHP's cURL functions. It's designed to be
used for easily accessing simple APIs.

# Usage

You're best bet is to check the tests/examples to see usage, as I'm awful at
documentation.

# Installation

Install the [Packagist archive](https://packagist.org/packages/henderjon/gooseberry)
using [Composer](http://getcomposer.org/).

# License

See LICENSE.md for the [BSD-3-Clause](http://opensource.org/licenses/BSD-3-Clause) license.

# TODO

I'd like to implement a way for users to add various auth methods either by trait or
by lambda. This would allow Gooseberry's use with APIs that use more complex authentication.

Right now there is no recovery from error (either cURL or HTTP). This is by design, however
there are APIs that are simply so unreliable that having a retry loop or a sleep() call
wouldn't hurt. I need to make some decisions in this regard.





