<?php
# Vytvoříme instanci pro práci s uživateli
$users = new Users($authority, $uid);

# Nasetujeme předané proměnné
$action = FW::get("action");
$data["actions"] = array();

# Změnit heslo
if ($action == "changePass") {
	$password = FW::post("password");
	$password2 = FW::post("password2");

	$data["actions"]["action"] = $action;
	$changePass = $users->changePass($password, $password2);

	if (is_array($changePass)) {
		$status = 0;
		$errors = $changePass;
	} else {
		if ($changePass == true) {
			$status = 1;
		} else {
			$status = 0;
		}
	}

	$data["actions"]["status"] = $status;
	$data["actions"]["errors"] = $errors;
}

# Vygenerujeme šablonu
$page = new Pagegen("setpass.html", $data);
exit;
?>
