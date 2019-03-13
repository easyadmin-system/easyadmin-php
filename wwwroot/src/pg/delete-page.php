<?php
# Instance
$pages = new Pages($authority, $uid);

# URl parametry
$pageId = FW::get("pageId");

# Smazání stránky
if ($pageId) {
	$status = $pages->deletePage($pageId);
}

# Data do šablon
$data["actions"] = array(
	"action" => "delete",
	"status" => $status
);
$data["pages"] = $pages->getPageList();

# Vygenerování šablony
$page = new Pagegen("pages.html", $data);
exit;
?>
