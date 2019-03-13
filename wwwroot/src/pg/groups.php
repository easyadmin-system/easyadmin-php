<?php
# Vytvoření objektů
$users = new Users($authority, $uid);
$groups = new Groups($authority, $uid, $users);

# Inicializace proměnných
$action = FW::get("action");
$groupId = FW::get("groupId");
if (!$action && $groupId) $action = "memberList";

# Načteme detailní info o skupině pro šablony
if ($groupId) {
	$data["groupDetail"] = $groups->getGroupNameById($groupId);
	$data["isGroupAuthorizedAdmin"] = $groups->isAuthorized($groupId);
	if (!$groups->groupIdExists($groupId)) $data["groupDoesNotExist"] = 1;
}

# Přidání skupiny
if ($action == "createGroup") {
	$name = FW::post("name");
	$create = $groups->createGroup($name);
	if (!is_array($create)) { $status = 200; } else { $status = 500; $errors = $create; }

# Editace skupiny
} elseif ($action == "editGroup" or $action == "memberList" or $action == "addMember" or $action == "removeMember" or $action == "setAdmin" or $action == "unsetAdmin") {

	# Přidání člena do skupiny
	if ($action == "addMember") {
		if ($groups->addMember(FW::post("user_id"), $groupId)) {
			$status = 200;
		} else {
			$status = 500;
		}
	}

	# Odebrání člena skupiny
	if ($action == "removeMember") {
		if ($groups->removeMember(FW::get("uid"), $groupId)) {
			$status = 200;
		} else {
			$status = 500;
		}
	}

	# Udělení správce
	if ($action == "setAdmin") {
		if ($groups->setAdmin(FW::get("uid"), $groupId)) {
			$status = 200;
		} else {
			$status = 500;
		}
	}

	# Odebrání správce
	if ($action == "unsetAdmin") {
		if ($groups->unsetAdmin(FW::get("uid"), $groupId)) {
			$status = 200;
		} else {
			$status = 500;
		}
	}

	# Načteme seznam členů s detaily
	$members = $groups->getMembersByGroupId($groupId);

# Smazání skupiny
} elseif ($action == "removeGroup") {
	$remove = $groups->removeGroup($groupId);
	if (!is_array($remove)) { $status = 200; } else { $status = 500; $errors = $remove; }
}

# Načtení seznamu skupin z DB
if (!$groupId ? $data["groups"] =
	$groups->groupList() : $data["nonMembers"] = $groups->getNonMembersByGroupId($groupId));


# Data pro šablony
if ($members) $data["members"] = $members;
$data["actions"] = array(
	"action" => $action,
	"errors" => $errors,
	"status" => $status
);
$data["requiredAuth"] = $groups->requiredAuth;
if ($groupId) $data["groupDetail"]["totalMembers"] = $groups->totalMembers($groupId);

# Vygenerujeme šablonu
$page = new Pagegen("groups.html", $data);
exit;
?>
