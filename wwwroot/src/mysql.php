<?php
$mysqlConnection = mysql_connect(Config::get("mysqlServer"), Config::get("mysqlName"), Config::get("mysqlPass"));

if ($mysqlConnection) {
	// Připojení bylo úspěšné
	$selectDB = mysql_select_db(Config::get("mysqlDatabase"));
	if (!$selectDB) {
		// Nepodařilo se načást databázi
		$page = new Pagegen("notice.html", $data = array("status" => 500));
		Dbg::log("Error: Cannot find MySQL database");
		exit;
	}
	
	mysql_query("SET NAMES utf8");
	mysql_query("SET COLLATION utf8_czech_ci");
} else {
	// Nepodařilo se připojit k DB
	$page = new Pagegen("notice.html", $data = array("status" => 500));
	Dbg::log("Error: Cannot connect to MySQL database");
	exit;
}
?>
