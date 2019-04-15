<?php
# Zjistíme z pěkné URL, jakou stránku budeme používat
if ($_SERVER["REDIRECT_URL"]) {
	$_GET["pg"] = substr($_SERVER["REDIRECT_URL"], 1);
}
$url = $_GET["pg"];

# Jednotlivé URL
if ($url == "login") {
	# Proces přihlášení
	require_once("src/login.php");
} else {
	# Ověření přihlášení
	session_start();
	if (Config::get("nonTempl") == 1) $_SESSION["login"] = Config::get("debug_userId");

	if (!$_SESSION["login"]) {
		if ($url) {
			$page = new Pagegen(
				"login.html",
				$data = array("status" => 419, "redirectUrl" => $_SERVER["REDIRECT_URL"])
			);
			exit;
		} else {
			$page = new Pagegen("login.html", $data = array("status" => 0));
			exit;
		}
	}

	# Aktualizace informací o uživateli
	$request = mysqli_query(
		$mysql->session,
		sprintf(
			"update %s_users set last_login = '%d', last_ip = '%s' where id like '%d'",
			Config::get("mysqlPrefix"),
			time(),
			$_SERVER["REMOTE_ADDR"],
			$_SESSION["login"])
	);
	if(!$request) {
		Dbg::log("Error: Cannot update user status");
		$page = new Pagegen("notice.html", $data = array("status" => 500));
		exit;
	}

	# Načtení informací o uživateli
	$request = mysqli_query(
		$mysql->session,
		sprintf(
			"select id, username, security_level, authority, allowed, sex, editor from %s_users where id like '%d'",
			Config::get("mysqlPrefix"),
			$_SESSION["login"]
		)
	);

	if (!$request) {
		Dbg::log("Error: Cannot read user details");
		$page = new Pagegen("notice.html", $data = array("status" => 500));
		exit;
	}

	$data = array(
		"user" => array()
	);
	list($id, $username, $security_level, $authority, $allowed, $sex, $editor) = mysqli_fetch_row($request);
	$uid = $id;

	# Vytvoříme instanci pro práci se systémovou konfigurací
	$sysconfig = new SysConfig($mysql, $authority, $uid);

	# Je sysém uzamčen?
	if ($sysconfig->getValue("system_lock") && $authority < 3) {
		$page = new Pagegen("notice.html", $data = array("status" => 403));
		exit;
	}

	# Informace o uživateli pro šablony
	$data["user"]["id"] = $id;
	$data["user"]["username"] = $username;
	$data["user"]["security_level"] = $security_level;
	$data["user"]["authority"] = $authority;
	$data["user"]["allowed"] = $allowed;
	$data["user"]["sex"] = $sex;
	$data["user"]["editor"] = $editor;

	$data["user"]["ip"] = $_SERVER["REMOTE_ADDR"];
	$data["application"]["title"] = $system["title"];
	$data["application"]["build"] = $system["build"];
	$data["application"]["starsSupport"] = Config::get("starsSupport");
	$data["application"]["admin"] = $dict["adminEmail"];
	$data["application"]["staticVersion"] = $dict["staticVersion"];
	$data["application"]["debugMode"] = $dict["debugMode"];
	$data["web"]["url"] = Config::get("webUrl");
	$data["web"]["site_root_url"] = $sysconfig->getValue("site_root_url");

	# Odhlášení
	if ($url == "logout") {
		session_destroy();
		unset($_SESSION["login"]);
		$page = new Pagegen("notice.html", $data = array("status" => -1));
	}

	# Výchozí modul
	if (!$url) $url = "overview";

	# Ostatní stránky - jednotlivé moduly
	$modFile = "src/pg/".$url.".php";
	if (file_exists($modFile)) {
		require_once($modFile);
	} else {
		$page = new Pagegen("notice.html", $data = array("status" => 404));
	}
}
?>
