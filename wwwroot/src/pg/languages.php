<?php
# Vytvoření PHP objektů
$langs = new Languages($mysql, $authority);

# Inicializace proměnných
$action = FW::get("action");
$code = FW::get("code");

# Přidání jazyka
if ($action == "addLanguage") {
	$addAction = $langs->addLanguage($code);
	if (!is_array($addAction)) { $status = 200; } else { $status = 500; $errors = $addAction; }

# Odebrání jazyka
} elseif ($action == "removeLanguage") {
	$removeAction = $langs->removeLanguage($code);
	if (!is_array($removeAction)) { $status = 200; } else { $status = 500; $errors = $removeAction; }
}

# Načtení seznamu jazyků z DB
$data["activeLanguages"] = $langs->getActiveLanguages();
$data["availableLanguages"] = $langs->getAvailableLanguages();

# Data pro šablony
$data["actions"] = array(
	"action" => $action,
	"errors" => $errors,
	"status" => $status
);
$data["requiredAuth"] = $languages->requiredAuth;

# Vygenerujeme šablonu
$page = new Pagegen("languages.html", $data);
exit;
?>
