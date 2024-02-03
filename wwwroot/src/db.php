<?php
$mysql = new MySQL();

if ($mysql->connect(Config::get("mysqlServer"), Config::get("mysqlName"), Config::get("mysqlPass"))) {
	// Připojení bylo úspěšné

	if (!$mysql->selectDB(Config::get("mysqlDatabase"))) {
		// Nepodařilo se načást databázi
		$page = new Pagegen("notice.html", $data = array("status" => 500));
		Dbg::log("Error: Cannot find MySQL database");
		exit;
	}

	mysqli_query($mysql->session, "SET NAMES utf8");
	// mysqli_query($mysql->session, "SET COLLATION utf8_general_ci");
} else {
	// Nepodařilo se připojit k DB
	$page = new Pagegen("notice.html", $data = array("status" => 500));
	Dbg::log("Error: Cannot connect to MySQL server");
	exit;
}
?>
