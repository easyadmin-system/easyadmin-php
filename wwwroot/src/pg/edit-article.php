<?php
# Instance
$articles = new Articles($mysql, $authority, $uid);
$pages = new Pages($mysql, $authority, $uid);

# URL parametry
$action = FW::get("action");
$articleUrl = FW::get("articleUrl");
$articleId = FW::get("articleId");

# Akce
if ($action == "create") {
	$ca = $articles->createArticle();
	$articleId = $ca;
	$status = 1;
	if (is_array($ca)) {
		$status = 0;
		$errors = $ca;
	}
} elseif ($action == "update") {
	$ea = $articles->updateArticle($articleId);
	$articleId = $ea;
	$status = 1;
	if (is_array($ea)) {
		$status = 0;
		$errors = $ea;
	}
}

# Obsah stránky
$data["page_detail"] = $articleContent = $articles->loadArticle($articleUrl, $articleId, true);

# Data do šablon
$data["articles"] = $articles->getArticles();
$data["actions"] = array(
	"action" => $action,
	"status" => $status,
	"errors" => $errors
);
$data["tinymce_config"] = json_encode($dict["tinymce"]);
$data["status"] = $status;
$data["errors"] = $errors;
$data["page"] = "edit-article";
$data["pages"] = $pages->getPageList();
$data["articlesUrl"] = $config["mods"]["articles"]["articlesUrl"];

# Vygenerování šablony
$page = new Pagegen("editor.html", $data);
exit;
?>
