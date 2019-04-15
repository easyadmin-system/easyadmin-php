<?php
# Vytvoříme instanci pro práci s uživateli
$users = new Users($mysql, $authority, $uid);

# Nasetujeme předané proměnné
$action = FW::get("action");
$data["actions"] = array();

# Provést uložení
if ($action == "save") {
	# Zpracování dat z formuláře
	$username = FW::post("username");
	$security_level = FW::num(FW::post("security_level"));
	$loginscreen = FW::num(FW::post("loginscreen"));
	$editor = FW::num(FW::post("editor"));
	$email = FW::post("email");

	$data["actions"]["action"] = $action;

	# Nastavení nových hodnot
	$newDetails["username"] = $username;
	$newDetails["security_level"] = $security_level;
	$newDetails["loginscreen"] = $loginscreen;
	$newDetails["email"] = $email;
	$newDetails["editor"] = $editor;

	$save = $users->savePersonalSettings($newDetails);

	if (is_array($save)) {
		$status = 0;
		$errors = $save;
	} else {
		if ($save == true) {
			$status = 1;
		} else {
			$status = 0;
		}
	}
	$data["actions"]["status"] = $status;
	$data["actions"]["errors"] = $errors;
}

# Zobrazení aktuálně uloženého nastavení
$userDetail = $users->getUserDetails($uid);
$data["userDetail"] = $userDetail;

# Vygenerujeme šablonu
$page = new Pagegen("settings.html", $data);
exit;
?>
