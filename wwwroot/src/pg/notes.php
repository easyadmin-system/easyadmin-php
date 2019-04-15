<?php
# Vytvoříme instanci pro práci s poznámkami
$notes = new Notes($mysql, $uid);

# Nasetujeme předané proměnné
$action = FW::get("action");
$notesData = FW::post("notes");

$data["actions"] = array();

# Uložit poznámky
if ($action == "save") {
	$data["actions"]["action"] = $action;

	$save = $notes->save($notesData);

	$data["actions"]["status"] = $save;
}

# Zobrazení aktuálních poznámek
list($notes) = mysqli_fetch_row(mysqli_query(
	$mysql->session,
	sprintf(
		"select notes from %s_users where id like '%d'",
		Config::get("mysqlPrefix"),
		$uid
	)
));

$data["notes"] = $notes;

# Vygenerujeme šablonu
$page = new Pagegen("notes.html", $data);
exit;
?>
