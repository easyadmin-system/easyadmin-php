<?php
# Zkontrolujeme, zda je přihlášený uživatel oprávněný používat tento plug-in
if ($authority < 2) {
	$page = new Pagegen("members.html", $data = array("status" => 403));
	exit;
}

# Vytvoříme instanci pro práci s uživateli
$users = new Users($mysql, $authority, $uid);

# Akce
$action = FW::get("action");
if (!$action) $showUserList = true;

# Nasetujeme předané proměnné
$username = FW::post("username");
$password = FW::post("password");
$sex = FW::num(FW::post("sex"));
$authority = FW::num(FW::post("authority"));
$uid = FW::num(FW::get("userId"));
if (!$uid) $uid = Fw::num(FW::post("userId"));
$allowed = FW::num(FW::post("allowed"));
$star = FW::num(FW::post("star"));
$email = FW::post("email");

$data["actions"] = array();


# Změnit heslo
if ($action == "changePass") {
	$data["actions"]["action"] = $action;
	if ($uid) {
		$changePass = $users->changePassByAdmin($uid, $password);

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
	} else {
		$data["actions"]["status"] = 0;
		$data["actions"]["errors"] = array("emptyUid" => 1);
	}

	$userDetail = $users->getUserDetails($uid);
	$data["userDetail"] = $userDetail;

	$showUserList = false;
}

# Provést editaci uživatele
if ($action == "modifyUser") {
	$data["actions"]["action"] = $action;
	if ($uid) {
		$newDetails["username"] = $username;
		$newDetails["authority"] = $authority;
		$newDetails["allowed"] = $allowed;
		$newDetails["sex"] = $sex;
		$newDetails["star"] = $star;
		$newDetails["email"] = $email;
		$modifyUser = $users->modifyUser($uid, $newDetails);

		if (is_array($modifyUser)) {
			$status = 0;
			$errors = $modifyUser;
		} else {
			if ($modifyUser == true) {
				$status = 1;
			} else {
				$status = 0;
			}
		}
		$data["actions"]["status"] = $status;
		$data["actions"]["errors"] = $errors;
	} else {
		$data["actions"]["status"] = 0;
		$data["actions"]["errors"] = array("emptyUid" => 1);
	}

	$userDetail = $users->getUserDetails($uid);
	$data["userDetail"] = $userDetail;

	$showUserList = false;
}

# Přidání uživatele
if ($action == "addUser") {
	$data["actions"]["action"] = $action;
	$addUser = $users->addUser($username, $password, $sex, $authority);
	if (is_array($addUser)) {
		$status = 0;
		$errors = $addUser;
	} else {
		if ($addUser == true) {
			$status = 1;
		} else {
			$status = 0;
		}
	}
	$data["actions"]["status"] = $status;
	$data["actions"]["errors"] = $errors;

	$showUserList = true;
}

# Smazání uživatele
if ($action == "removeUser") {
	$data["actions"]["action"] = $action;
	if ($uid) {
		$removeUser = $users->removeUser($uid);
		if (is_array($removeUser)) {
			$status = 0;
			$errors = $removeUser;
		} else {
			if ($removeUser == true) {
				$status = 1;
			} else {
				$status = 0;
			}
		}
		$data["actions"]["status"] = $status;
		$data["actions"]["errors"] = $errors;
	} else {
		$data["actions"]["status"] = 0;
		$data["actions"]["errors"] = array("emptyUid" => 1);
	}

	$showUserList = true;
}

# Formulář editace uživatele nebo změny hesla
if ($action == "editUser" || $action == "newPass") {
	$data["actions"]["action"] = $action;

	if ($uid) {
		if (!$users->uidExists($uid)) {
			$err["uidDoesNotExists"] = 1;
			$status = 0;
		} else {
			$userDetail = $users->getUserDetails($uid);
			if ($userDetail) {
				$status = 1;
			} else {
				$status = 0;
			}
		}

		$data["actions"]["status"] = $status;
		$data["actions"]["errors"] = $err;
		$data["userDetail"] = $userDetail;
	} else {
		$data["actions"]["status"] = 0;
		$data["actions"]["errors"] = array("emptyUid" => 1);
	}

	$showUserList = false;
}

# Zobrazení seznamu uživatelů
if ($showUserList) {
	$request = mysqli_query(
		$mysql->session,
		sprintf(
			"select id, username, authority, registration, last_login, sex, star from %s_users order by id",
			Config::get("mysqlPrefix")
		)
	);

	$userList = array();
	$i=0;
	while (list($uid, $username, $authority, $registration, $last_login, $sex, $star) = mysqli_fetch_row($request)) {
		$userList["user"][$i] = array(
			"uid" => $uid,
			"username" => $username,
			"authority" => $authority,
			"registration" => $registration,
			"last_login" => $last_login,
			"sex" => $sex,
			"star" => $star
		);
		$i++;
	}

	$data["users"] = $userList;
}
$data["actions"]["showUserList"] = $showUserList;

# Vygenerujeme šablonu
$page = new Pagegen("members.html", $data);
exit;
?>
