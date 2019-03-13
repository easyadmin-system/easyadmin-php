<?php
# Konfigurace MySQL databáze
$config["mysqlServer"] = "localhost";
$config["mysqlName"] = "root";
$config["mysqlPass"] = "aaa";
$config["mysqlDatabase"] = "easyadmin";
$config["mysqlPrefix"] = "eas";

# URL veřejné části webu
$config["webUrl"] = "http://easyadmin.cz";

# Budeme zobrazovat hvězdičky?
$config["starsSupport"] = 1;

# Systémová konfigurace načítající se z databáze
$config["sys"] = array(
	"site_title" => array(
		"type" => "text",
		"auth" => 3
	),
	"site_description" => array(
		"type" => "text",
		"auth" => 3
	),
	"site_keywords" => array(
		"type" => "text",
		"auth" => 3
	),
	"site_root_url" => array(
		"type" => "text",
		"auth" => 4
	),
	"login_timeout" => array(
		"type" => "number",
		"auth" => 4
	),
	"system_lock" => array(
		"type" => "checkbox",
		"auth" => 3
	),
	"updates_check" => array(
		"type" => "checkbox",
		"auth" => 4
	),
	"updates_check_interval" => array(
		"type" => "number",
		"auth" => 4
	)
);

# Nastavení pro jednotlivé moduly
$config["mods"] = array(

	# Články
	"articles" => array(
		"articlesUrl" => "clanky"
	)
);

# Jaký typ výpisu chyb budeme zapisovat do error logu
ini_set("error_reporting", E_ALL & ~E_NOTICE & ~E_STRICT);
?>
