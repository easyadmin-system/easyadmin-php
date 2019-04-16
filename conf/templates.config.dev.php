<?php
# Debug
$dict["debugMode"] = 1;

# Email admina
$dict["adminEmail"] = "jan@elznic.com";

# Statická verze šablon
$dict["staticVersion"] = 1;

# Výchozí jazyk systému
$dict["defaultLanguage"] = "cs";

# WYSIWYG editor
$dict["tinymce"] = array(
	"content_css" => "/css/screen.css",
	"style_formats" => array(
		array("title" => "Standardní odstavec", "block" => "p"),
		array("title" => "Perex", "block" => "p", "classes" => "perex"),
		array("title" => "Nadpis 2", "block" => "h2"),
		array("title" => "Nadpis 3", "block" => "h3"),
		array("title" => "Nadpis 4", "block" => "h4"),
		array("title" => "Nadpis 5", "block" => "h5"),
		array("title" => "Nadpis 6", "block" => "h6")
	)
);
?>
