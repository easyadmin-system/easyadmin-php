<?php
# Instance
$pages = new Pages($mysql, $authority, $uid);
$languages = new Languages($mysql, $authority);

# Dostupné jazyky
$data["activeLanguages"] = $languages->getActiveLanguages();
$data["availableLanguages"] = $languages->getAvailableLanguages();

# Data do šablon
$data["pages"] = $pages->getPageList();

# Vygenerování šablony
$page = new Pagegen("pages.html", $data);
exit;
?>
