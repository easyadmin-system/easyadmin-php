<?php
class Groups
{
	/**
	 * Conscructor
	 */
	function Groups($selfAuthority, $selfUid, $users) {
		$this->selfAuthority = $selfAuthority;
		$this->selfUid = $selfUid;
		$this->users = $users;

		# Úroveň globálního oprávnění, které je potřeba pro všechny úkony ve skupinách
		$this->requiredAuth = 2;
	}

	/**
	 * Založení nové skupiny
	 */
	function createGroup($name) {
		if ($this->selfAuthority < $this->requiredAuth) return array("insufficientPermissions" => 1);

		if (strlen($name) < 3) $err["length"] = 1;
		if ($this->groupExists($name)) $err["exists"] = 1;

		if (sizeof($err)) {
			return $err;
		} else {
			if (MySQL::insertRow("groups", array("name" => $name))) {
				return true;
			}
			Dbg::log("Error: Cannot create new group");
			return false;
		}
	}

	/**
	 * Smazání skupiny
	 */
	function removeGroup($groupId) {
		if ($this->selfAuthority < $this->requiredAuth) return array("insufficientPermissions" => 1);
		if (!$this->groupIdExists($groupId)) return array("doesNotExist" => 1);
		return MySQL::deleteRow("groups", "id", $groupId);
	}

	/**
	 * Zjistíme, zda-li již skupina s daným názvem neexistuje
	 */
	function groupExists($name) {
		return MySQL::countRows("groups", "name", $name);
	}

	/**
	 * Zjistíme, zda-li již skupina s daným ID neexistuje
	 */
	function groupIdExists($groupId) {
		return MySQL::countRows("groups", "id", $groupId);
	}

	/**
	 * Načtení abecedního seznamu skupin
	 */
	function groupList() {
		$groups = MySQL::getList(array("id", "name"), "groups", false, array("name"));
		for ($i=0; count($groups) > $i; $i++) {
			$groups[$i]["members"] = MySQL::countRows("groups_members", "group_id", $groups[$i]["id"]);
		}

		if ($groups) return $groups;
		return false;
	}

	/**
	 * Vrátí název skupiny podle jejího ID
	 */
	function getGroupNameById($id) {
		return MySQL::selectRow("groups", "id", $id);
	}

	/**
	 * Načtení seznamu členů skupiny
	 */
	function getMembersByGroupId($groupId) {
		$members = MySQL::getList(
			array("user_id", "admin"), "groups_members", array("group_id" => $groupId)
		);
		if (!$members) return false;
		foreach ($members as $i => $userId) {
			$members[$i] = array(
				"uid" => $userId["user_id"],
				"username" => $this->users->getUserNameByUid($userId["user_id"]),
				"admin" => $userId["admin"]
			);
		}
		return $members;
	}

	/**
	 * Načte všechny uživatele mimo členů aktuální skupiny
	 */
	function getNonMembersByGroupId($groupId) {
		$allUsers = $this->users->getUserListByUserName();
		$members = $this->getMembersByGroupId($groupId);
		$nonMembers = array();

		$membersToCompare = array();
		foreach ($members as $k => $v) {
			array_push($membersToCompare, array("uid" => $v["uid"], "username" => $v["username"]));
		}

		foreach ($allUsers["user"] as $user) {
			if (!in_array($user, $membersToCompare)) array_push($nonMembers, $user);
		}

		return $nonMembers;
	}

	/**
	 * Přidá člena do skupiny na základě user ID
	 */
	function addMember($uid, $groupId) {
		if ($this->selfAuthority < $this->requiredAuth) return array("insufficientPermissions" => 1);

		if ($this->isAuthorized($groupId)) {
			return MySQL::insertRow(
				"groups_members", array("user_id" => $uid, "group_id" => $groupId)
			);
		}
	}

	/**
	 * Odebere člena skupiny
	 */
	function removeMember($uid, $groupId) {
		if (!$this->isAuthorized($groupId)) return false;
		if (!$this->groupIdExists($groupId)) return false;
		return MySQL::deleteRow("groups_members", array("user_id" => $uid, "group_id" => $groupId));
	}

	/**
	 * Zjistí počet členů ve skupině
	 */
	function totalMembers($groupId) {
		return MySQL::countRows("groups_members", "group_id", $groupId);
	}

	/**
	 * Zjistí, zda-li je aktuální uživatel autorizován k úkonům správce
	 */
	function isAuthorized($groupId) {
		if ($this->isAdmin($groupId) or $this->selfAuthority >= $this->requiredAuth) return true;
		return false;
	}

	/**
	 * Zjistí, zda-li uživatel má oprávnění správce
	 */
	function isAdmin($groupId) {
		return MySQL::countRows("groups_members", array("user_id" => $this->selfUid, "group_id" => $groupId, "admin" => 1));
	}

	/**
	 * Udělí uživateli oprávnění správce
	 */
	function setAdmin($uid, $groupId) {
		if (!$this->isAuthorized($groupId)) return false;
		return MySQL::updateRow(
			"groups_members", array("user_id" => $uid, "group_id" => $groupId), false, array("admin" => 1)
		);
	}

	/**
	 * Odebere uživateli oprávnění správce
	 */
	function unsetAdmin($uid, $groupId) {
		if ($this->isAuthorized($groupId)) {
			return MySQL::updateRow(
				"groups_members", array("user_id" => $uid, "group_id" => $groupId), false, array("admin" => 0)
			);
		}
		return false;
	}
}
?>
