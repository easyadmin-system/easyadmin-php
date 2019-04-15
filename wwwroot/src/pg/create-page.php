<?php
# Instance tříd
$pages = new Pages($mysql, $authority, $uid);

# Výchozí hodnoty pro novou stránku
$data["page_detail"] = array(
	"id" => 0,
	"url" => "",
	"public" => 1,
	"static" => 1,
	"title" => "",
	"meta_description" => "",
	"priority" => "0.5",
	"edit_frequency" => "monthly",
	"noindex" => 0,
	"keywords" => "",
	"publish_date" => 0,
	"author" => "",
	"content" => array()
);

# Data do šablon
$data["pages"] = $pages->getPageList();
$data["tinymce_config"] = json_encode($dict["tinymce"]);
$data["page"] = "create-page";
$page = new Pagegen("editor.html", $data);
exit;
?>
