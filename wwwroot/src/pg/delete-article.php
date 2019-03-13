<?php
# Instance
$articles = new Articles($authority, $uid);

# URl parametry
$articleId = FW::get("articleId");

# Smazání stránky
if ($articleId) {
	$status = $articles->deleteArticle($articleId);
}

# Data do šablon
$data["actions"] = array(
	"action" => "delete",
	"status" => $status
);
$data["articles"] = $articles->getArticles();

# Vygenerování šablony
$page = new Pagegen("articles.html", $data);
exit;
?>
