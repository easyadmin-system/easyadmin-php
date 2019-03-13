<?php
# Instance
$pages = new Pages($authority, $uid);

# Data do šablon
$data["pages"] = $pages->getPageList();

# Vygenerování šablony
$page = new Pagegen("pages.html", $data);
exit;
?>
