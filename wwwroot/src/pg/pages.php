<?php
# Instance
$pages = new Pages($mysql, $authority, $uid);

# Data do šablon
$data["pages"] = $pages->getPageList();

# Vygenerování šablony
$page = new Pagegen("pages.html", $data);
exit;
?>
