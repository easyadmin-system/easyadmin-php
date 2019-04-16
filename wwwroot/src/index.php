<?php
# Knihovny / třídy
require_once("src/lib/class.helpers.php");
require_once("src/lib/class.mysqli.php");
require_once("src/lib/class.dbglog.php");
require_once("src/lib/class.pagegen.php");
require_once("src/lib/class.users.php");
require_once("src/lib/class.groups.php");
require_once("src/lib/class.languages.php");
require_once("src/lib/class.contents.php");
require_once("src/lib/class.articles.php");
require_once("src/lib/class.pages.php");
require_once("src/lib/class.sysconfig.php");
require_once("src/lib/class.notes.php");

# Konfigurace
require_once(apache_getenv('APP_CONFIG_FILE'));
require_once(apache_getenv('TEMPL_CONFIG_FILE'));
require_once("src/vars.php");
require_once("src/configinit.php");

# Databáze
require_once("src/db.php");

# Helpery
require_once("src/helpers/translations.php");

# Routing
require_once("src/router.php");
?>
