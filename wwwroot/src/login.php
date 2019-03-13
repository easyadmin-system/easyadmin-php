<?php
$name = FW::mres($_POST["username"]);
$pass = FW::mres($_POST["password"]);
$redirectUrl = $_POST["redirectUrl"];


# Načteme informace o uživateli
$request = mysql_query(
	sprintf(
		"select count(id), id, username, pass, authority, allowed, login_count from %s_users where username like '%s'",
		Config::get("mysqlPrefix"),
		$name
	)
);

if (!$request) {
	Dbg::log("Error: Cannot read data from MySQL");
	$page = new Pagegen("notice.html", $data = array("status" => 500));
	exit;
}

list($count, $uid, $username, $password, $authority, $allowed, $login_count) = mysql_fetch_row($request);

# Existuje uživatel?
if ($count < 1) {
	$page = new Pagegen("login.html", array("status" => -2));
	exit;
}

# Porovnání hesel
if (md5($pass) != $password) {
	$page = new Pagegen("login.html", array("status" => -1, "username" => $username));
	exit;
}

# Má uživatel aktivní účet? (neplatí pro superadminy)
if ($allowed != 1 && $authority < 3) {
	$page = new Pagegen("login.html", array("status" => -3));
	exit;
}

# Vše je v pořádku, přihlásíme uživatele
session_start();
$_SESSION["login"] = $uid;

# Aktualizace informací o uživateli
$request = mysql_query(
	sprintf(
		"update %s_users set last_login = '%d', last_ip = '%s', login_count = '%d' where id = '%d'",
		Config::get("mysqlPrefix"), time(), $_SERVER["REMOTE_ADDR"], $login_count++, $uid
	)
);

if (!$request) {
	Dbg::log("Error: Cannot update last login data about user");
	$page = new Pagegen("notice.html", $data = array("status" => 500));
	exit;
}

# Zaznamenáme přístup do systému
$request = mysql_query(
	sprintf(
		"insert into %s_user_logins(time, name, ip, httpua) values('%d', '%s', '%s', '%s')",
		Config::get("mysqlPrefix"),
		time(),
		$username,
		$_SERVER["REMOTE_ADDR"],
		substr($_SERVER["HTTP_USER_AGENT"], 0, 255)
	)
);

if (!$request) {
	Dbg::log("Error: Cannot update last access");
	$page = new Pagegen("notice.html", $data = array("status" => 500));
	exit;
}

# Přejdeme na stránku s přehledem
if ($redirectUrl) {
	header(sprintf("Location: %s", $redirectUrl));
	exit;
}
header("Location: /overview");
?>
