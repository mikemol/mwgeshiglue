This is a MediaWiki extension designed to tie MediaWiki to GeSHi, primarily with
the concerns of Rosetta Code's[1] use case. As such, it only uses a subset of
GeSHi's available features.

To install:

1) Copy MWGeSHiGlue.php to $IP/extensions/
2) Download GeSHi[2], install to $IP/extensions/geshi/
3) To $IP/LocalSettings.php, add:
   require_once("$IP/extensions/MWGeSHiGlue.php");

To use:

Wrap your code snippet with tags named <lang>, like so:

 <lang perl>use strict;
 print "Hello, world!";</lang>

or

 <lang bash>echo "Hello, world!";</lang>

To see a list of supported languages (such as might be useful on a documentation
page), try:

 <lang list></lang>

Special configurations:

If you wish to put GeSHi somewhere other than $IP/extensions (Say, for example,
your distribution packages GeSHi), then put the following in your
LocalSettings.php file, prior to the require_once line pulling in MWGeSHiGlue:

  $mwggGeSHiPath = "/path/to/geshi"

[1] http://rosettacode.org/
[2] http://qbnz.com/highlighter/
