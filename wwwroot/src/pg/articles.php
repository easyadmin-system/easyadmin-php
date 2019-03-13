<?php
# Instance
$articles = new Articles($authority, $uid);
$pages = new Pages($authority, $uid);

# Data do šablon
$data["pages"] = $pages->getPageList();
$data["articles"] = $articles->getArticles();
$data["articlesUrl"] = $config["mods"]["articles"]["articlesUrl"];

# Vygenerování šablony
$page = new Pagegen("articles.html", $data);
exit;
?>
