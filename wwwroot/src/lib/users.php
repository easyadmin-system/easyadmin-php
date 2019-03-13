<?php
class Users
{
	/**
	 * Conscructor
	 **/
	function Users($selfAuthority, $selfUid) {
		$this->selfAuthority = $selfAuthority;
		$this->selfUid = $selfUid;
	}

	/**
	 * Vrátí úroveň oprávnění
	 **/
	function getAuthority($uid) {
		list($authority) = mysql_fetch_row(mysql_query(
			sprintf(
				"select authority from %s_users where id like '%d'",
				Config::get("mysqlPrefix"),
				$uid
			)
		));
		return $authority;
	}

	/**
	 * Přidání uživatele
	 **/
	function addUser($username, $password, $sex, $authority) {
		if (!$this->validName($username)) $err["validity"] = 1;
		if ($this->userExists($username)) $err["exists"] = 1;
		if (!$this->checkPassLength($password)) $err["password"] = 1;
		if (!$this->validAuthority($authority)) $err["authority"] = 1;

		if (sizeof($err) > 0) {
			return $err;
		} else {
			$request = mysql_query(
				sprintf(
					"insert into %s_users (username, pass, authority, allowed, registration, last_login, sex) values ('%s', '%s', '%d', '1', '%d', '%d', '%d')",
					Config::get("mysqlPrefix"),
					$username,
					md5($password),
					$authority,
					time(),
					time(),
					$sex
				)
			);
			if ($request) {
				return true;
			} else {
				Dbg::log("Error: Cannot create new user");
				return false;
			}
		}
	}

	/**
	  * Validace uživatelského jména
	  **/
	function validName($username) {
		if (preg_match("/^[a-zA-Z][a-zA-Z0-9._-]{1,18}[a-zA-Z0-9_-]$/", $username)) {
			return true;
		}
	}

	/**
	  * Zjištění, zda-li uživatel již existuje
	  **/
	function userExists($username) {
		list($count) = mysql_fetch_row(mysql_query(
			sprintf(
				"select count(id) from %s_users where username like '%s'",
				Config::get("mysqlPrefix"),
				$username
			)
		));

		return $count;
	}

	/**
	  * Vrátí uživatelské UID podle uživatelského jména
	  **/
	function getUidByUserName($username) {
		list($uid) = mysql_fetch_row(mysql_query(
			sprintf(
				"select id from %s_users where username like '%s'",
				Config::get("mysqlPrefix"),
				$username
			)
		));

		return $uid;
	}

	/**
	  * Vrátí uživatelské jméno podle UID uživatele
	  **/
	function getUserNameByUid($uid) {
		list($username) = mysql_fetch_row(mysql_query(
			sprintf(
				"select username from %s_users where id like '%d'",
				Config::get("mysqlPrefix"),
				$uid
			)
		));

		return $username;
	}

	/**
	  * Zjištění, zda-li uživatel již existuje podle UID
	  **/
	function uidExists($uid) {
		list($count) = mysql_fetch_row(mysql_query(
			sprintf(
				"select count(id) from %s_users where id like '%d'",
				Config::get("mysqlPrefix"),
				$uid
			)
		));

		return $count;
	}

	/**
	  * Kontrola a validace oprávnění
	  **/
	function validAuthority($level) {
		if (!preg_match("/^[0-5]$/", $level)) return false;
		if ($this->selfAuthority < $level) return false;
		return true;
	}

	/**
	 * Odstranit uživatele
	 **/
	function removeUser($uid) {
		if (!$this->uidExists($uid)) $err["uidDoesNotExists"] = 1;
		if ($this->selfUid == $uid) $err["selfAccountRemove"] = 1;
		if ($this->selfAuthority < $this->getAuthority($uid)) $err["insufficientPermissions"] = 1;

		if (sizeof($err) > 0) {
			return $err;
		} else {
			$request = mysql_query(
				sprintf(
					"delete from %s_users where id like '%d'",
					Config::get("mysqlPrefix"),
					$uid
				)
			);

			if ($request) {
				return true;
			} else {
				Dbg::log("Error: Cannot delete user");
				return false;
			}
		}
	}

	/**
	 * Změní heslo aktuálně přihlášeného uživatele
	 **/
	function changePass($pass, $pass2) {
		if ($pass != $pass2) $err["notSame"] = 1;
		if (!$this->checkPassLength($pass)) $err["passLength"] = 1;

		if (sizeof($err) > 0) {
			return $err;
		} else {
			return $this->updatePass($this->selfUid, $pass);
		}
	}

	/**
	 * Změní heslo pro daného uživatele
	 **/
	function changePassByAdmin($uid, $pass) {
		if (!$this->checkPassLength($pass)) $err["passLength"] = 1;
		if (($this->selfAuthority < $this->getAuthority($uid)) || $this->selfAuthority < 3) $err["notAuthorized"] = 1;

		if (sizeof($err) > 0) {
			return $err;
		} else {
			return $this->updatePass($uid, $pass);
		}
	}

	/**
	 * Nastaví nové heslo v databázi
	 **/
	function updatePass($uid, $pass) {
		$request = mysql_query(
			sprintf(
				"update %s_users set pass = '%s' where id like '%d'",
				Config::get("mysqlPrefix"),
				md5($pass),
				$uid
			)
		);

		if ($request) {
			return true;
		} else {
			Dbg::log("Error: Cannot change password");
			return false;
		}
	}

	/**
	 * Zkontroluje délku hesla
	 **/
	function checkPassLength ($pass) {
		if (strlen($pass) >= 5) {
			return true;
		}
	}

	/**
	 * Zjistí, zda-li jsou vyplněna všechna políčka při editaci uživatele
	 **/
	function checkForRequiredWhenModifing($uid, $d) {
		if (!$d["username"] or !$d["authority"] or !$d["allowed"] or !$d["sex"] or !$d["star"]) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Zjistí, zda-li jsou vyplněna všechna políčka při ukládání osobního nastavení
	 **/
	function checkForRequiredWhenSaving($uid, $d) {
		if (!$d["username"] or !$d["security_level"] or !$d["editor"]) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Uložit osobní nastavení
	 **/
	function savePersonalSettings($newDetails) {
		$oldDetails = $this->getUserDetails($this->selfUid);
		if (!$this->checkForRequiredWhenSaving($uid, $newDetails)) $err["someValsEmpty"];
		if (strtolower($oldDetails["username"]) != strtolower($newDetails["username"])) $err["notSameUserName"] = 1;

		if (sizeof($err) > 0) {
			return $err;
		} else {
			$opt = sprintf("username = '%s', ", $newDetails["username"]);
			$opt .= sprintf("email = '%s', ", $newDetails["email"]);
			$opt .= sprintf("security_level = '%s', ", $newDetails["security_level"]);
			$opt .= sprintf("loginscreen = '%s', ", $newDetails["loginscreen"]);
			$opt .= sprintf("editor = '%d'", $newDetails["editor"]);
			$request = mysql_query(
				sprintf(
					"update %s_users set %s where id like '%d'",
					Config::get("mysqlPrefix"),
					$opt,
					$this->selfUid
				)
			);

			if ($request) {
				return true;
			} else {
				Dbg::log("Error: Cannot save user settings");
				return false;
			}
		}
	}

	/**
	 * Editace uživatele
	 **/
	function modifyUser($uid, $newDetails) {
		$oldDetails = $this->getUserDetails($uid);
		if (!$this->checkForRequiredWhenModifing($uid, $newDetails)) $err["someValsEmpty"];
		if (!$this->uidExists($uid)) $err["uidDoesNotExists"] = 1;
		if (!$this->isAuthorizedSuperAdmin($uid, $oldDetails, $newDetails)) $err["isNotPrivilegedToSuperModifies"] = 1;
		if (!$this->isAuthorizedToModifyOptions($uid, $oldDetails, $newDetails)) $err["isNotAuthorizedToModifySuperOptions"] = 1;
		// Fix me
		//if ($newDatails["authority"] > $oldDetails["authority"]) $err["isNotAuthorizedToElevatePrivileges"] = 1;

		if (sizeof($err) > 0) {
			return $err;
		} else {
			$opt = sprintf("username = '%s', ", $newDetails["username"]);
			$opt .= sprintf("authority = '%d', ", $newDetails["authority"]);
			$opt .= sprintf("allowed = '%d', ", $newDetails["allowed"]);
			$opt .= sprintf("sex = '%d', ", $newDetails["sex"]);
			$opt .= sprintf("star = '%d', ", $newDetails["star"]);
			$opt .= sprintf("email = '%s'", $newDetails["email"]);
			$request = mysql_query(
				sprintf(
					"update %s_users set %s where id like '%d'",
					Config::get("mysqlPrefix"),
					$opt,
					$uid
				)
			);

			if ($request) {
				return true;
			} else {
				Dbg::log("Error: Cannot do user modifications");
				return false;
			}
		}
	}

	/**
	 * Má uživatel právo měnit uživ. jméno, heslo, oprávnění a pohlaví uživateli s daným UID?
	 **/
	function isAuthorizedToModifyOptions($uid, $oldDetails, $newDetails) {
		if (
			(
				($oldDetails["username"] != $newDetails["username"]) or
				($oldDetails["authority"] != $newDetails["authority"]) or
				($oldDetails["sex"] != $newDetails["sex"])
			) &&
			($this->selfAuthority < $oldDetails["authority"])
		) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Má uživatel právo měnit uživ. jméno, heslo, oprávnění, pohlaví a hvězdu?
	 **/
	function isAuthorizedSuperAdmin($uid, $oldDetails, $newDetails) {
		if (
			(
				($oldDetails["username"] != $newDetails["username"]) or
				($oldDetails["authority"] != $newDetails["authority"]) or
				($oldDetails["sex"] != $newDetails["sex"]) or
				($oldDetails["star"] != $newDetails["star"])
			) &&
			($this->selfAuthority < 3)
		) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Načte informace o uživateli
	 **/
	function getUserDetails($uid) {
		$request = mysql_query(
			sprintf(
				"select id, username, security_level, authority, allowed, sex, star, email, editor, loginscreen from %s_users where id like '%d'",
				Config::get("mysqlPrefix"),
				$uid
			)
		);
		if ($request) {
			$details = mysql_fetch_array($request);

			$i = 0;
			foreach ($details as $key => $val) {
				if ($key == $i) {
					$i++;
				} else {
					$output[$key] = $val;
				}
			}
			return $output;
		} else {
			Dbg::log("Error: Cannot read data about user");
			return false;
		}
	}

	/**
	 * Vrátí seznam uživatelů setřízený uživatelského jména
	 **/
	function getUserListByUserName() {
		$request = mysql_query(
			sprintf(
				"select id, username from %s_users order by username",
				Config::get("mysqlPrefix")
			)
		);

		$users = array();
		$i=0;
		while (list($id, $username) = mysql_fetch_row($request)) {
			$users["user"][$i] = array(
				"uid" => $id,
				"username" => $username
			);
			$i++;
		}

		return $users;
	}
}
?>
