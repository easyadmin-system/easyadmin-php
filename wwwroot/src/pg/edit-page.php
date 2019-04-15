<?php
# Instance
$pages = new Pages($mysql, $authority, $uid);
$languages = new Languages($mysql, $authority);

# Dostupné jazyky
$data["activeLanguages"] = $languages->getActiveLanguages();
$data["availableLanguages"] = $languages->getAvailableLanguages();


# URL parametry
$action = FW::get("action");
$pageUrl = FW::get("pageUrl");
$pageId = FW::get("pageId");

# Akce
if ($action == "create") {
	$cp = $pages->createPage();
	$pageId = $cp;
	$status = 1;
	if (is_array($cp)) {
		$status = 0;
		$errors = $cp;
	}
} elseif ($action == "update") {
	$ep = $pages->updatePage($pageId);
	$pageId = $ep;
	$status = 1;
	if (is_array($ep)) {
		$status = 0;
		$errors = $ep;
	}
}

# Obsah stránky
$data["page_detail"] = $pageContent = $pages->loadPage($pageUrl, $pageId, true);

# Data do šablon
$data["pages"] = $pages->getPageList();
$data["actions"] = array(
	"action" => $action,
	"status" => $status,
	"errors" => $errors
);
$data["tinymce_config"] = json_encode($dict["tinymce"]);
$data["status"] = $status;
$data["errors"] = $errors;
$data["page"] = "edit-page";

# Vygenerování šablony
$page = new Pagegen("editor.html", $data);
exit;
?>
