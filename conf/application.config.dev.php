<?php
$config = array(

	# Konfigurace MySQL databáze
	"mysqlServer" => "__DB_HOST__",
	"mysqlName" => "__DB_USERNAME__",
	"mysqlPass" => "__DB_PASSWORD__",
	"mysqlDatabase" => "__DB_DATABASE__",
	"mysqlPrefix" => "__DB_TABLE_PREFIX__",

	# URL veřejné části webu
	"webUrl" => "__DOMAIN__",

	# Dostupné jazyky
	"availableLanguages" => array(
		"cs" => array("language" => "Česky"),
		"en" => array("language" => "Anglicky"),
		"fr" => array("language" => "Francouzsky"),
		"hr" => array("language" => "Chorvatsky"),
		"it" => array("language" => "Italsky"),
		"hu" => array("language" => "Maďarsky"),
		"de" => array("language" => "Německy"),
		"pl" => array("language" => "Polsky"),
		"ru" => array("language" => "Rusky"),
		"sk" => array("language" => "Slovensky"),
		"es" => array("language" => "Španělsky"),
		"ua" => array("language" => "Ukrajinsky")
	),

	# Budeme zobrazovat hvězdičky?
	"starsSupport" => 0,

	# Systémová konfigurace načítající se z databáze
	"sys" => array(
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
	),

	# Nastavení pro jednotlivé moduly
	"mods" => array(

		# Články
		"articles" => array(
			"articlesUrl" => "clanky"
		)
	)

);

# Jaký typ výpisu chyb budeme zapisovat do error logu
ini_set("error_reporting", E_ALL & ~E_NOTICE & ~E_STRICT);
?>
