<?php
# Zkontrolujeme, zda je přihlášený uživatel oprávněný používat tento plug-in
if ($authority < 2) {
	$page = new Pagegen("sysconfig.html", $data = array("status" => 403));
	exit;
}

# Inicializace proměnných na začátku
$action = FW::get("action");
$options = $config["sys"];

# Povolené klíče
$validKeys = array();
foreach ($options as $key => $val) array_push($validKeys, $key);

# Uložení hodnot
if ($action == "save") {
	$status = $sysconfig->saveValues($validKeys);
}

# Načtení konfigurace
$pureData = $sysconfig->getValues();

# Spojíme data z konfiguračního souboru s těmi z databáze
foreach ($pureData as $key => $val) {
	$options[$val["variable"]]["value"] = $val["value"];

	# Zabráníme zobrazení hodnot pro vyšší autentizační stupně
	if ($options[$val["variable"]]["auth"] > $authority) {
		unset($options[$val["variable"]]);
	}
}

# Data do šablon
$data["options"] = $options;
$data["actions"]["action"] = $action;
$data["actions"]["status"] = $status;

# Vygenerujeme šablonu
$page = new Pagegen("sysconfig.html", $data);
exit;
?>
