#!/bin/bash
#
# Komprimace JavaScript souborů pomocí nástroje kjscompress
#

#!/bin/sh

set -e

# komprimace JS scriptu
DIR_JS_LIB=../wwwroot/js/lib
DIR_JS_APP=../wwwroot/js/app
DIR_JS_OUT=../wwwroot/js


cat $DIR_JS_LIB/*.js | /usr/bin/kjscompress  > $DIR_JS_OUT/js_lib.js
cat $DIR_JS_APP/*.js | /usr/bin/kjscompress  > $DIR_JS_OUT/js_all.js

