<?php
# Instance
$articles = new Articles($mysql, $authority, $uid);
$pages = new Pages($mysql, $authority, $uid);

# Data do šablon
$data["pages"] = $pages->getPageList();
$data["articles"] = $articles->getArticles();
$data["articlesUrl"] = $config["mods"]["articles"]["articlesUrl"];

# Vygenerování šablony
$page = new Pagegen("articles.html", $data);
exit;
?>
