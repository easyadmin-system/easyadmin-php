<?php
# Knihovny
require_once("src/lib/framework.php");
require_once("src/lib/mysqlmini.php");
require_once("src/lib/dbglog.php");
require_once("src/lib/pagegen.php");
require_once("src/lib/users.php");
require_once("src/lib/groups.php");
require_once("src/lib/contents.php");
require_once("src/lib/articles.php");
require_once("src/lib/pages.php");
require_once("src/lib/sysconfig.php");
require_once("src/lib/notes.php");

# Konfigurace
require_once("config/templates.conf");
require_once("config/application.conf");
require_once("src/vars.php");
require_once("src/configinit.php");

# Procesy
require_once("src/mysql.php");
require_once("src/page.php");
?>
