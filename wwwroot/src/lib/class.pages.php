<?php
class Pages
{
	/**
	 * Conscructor
	 */
	function __construct($mysql, $selfAuthority, $selfUid) {
		$this->mysql = $mysql;
		if (!$selfAuthority) return false;
		if (!$selfUid) return false;
		$this->selfAuthority = $selfAuthority;
		$this->selfUid = $selfUid;
	}

	/**
	 * Smaže stránku
	 */
	public function deletePage($pageId) {
		return $this->mysql->deleteRow("pages", "id", $pageId);
	}

	/**
	 * Vytviří novou stránku
	 */
	public function createPage() {
		$err = array();

		$page = FW::validPostData(array(
			"language", "title", "url", "content", "public", "static", "meta_description",
			"keywords", "priority", "edit_frequency", "noindex"
		));
		if (!$page["language"]) $err["languageIsEmpty"] = 1;
		if (!$page["title"]) $err["titleIsEmpty"] = 1;
		if (!$page["url"]) $err["urlIsEmpty"] = 1;
		$page["url"] = FW::parseRelativeUrl($page["url"]);
		if ($this->selfAuthority < 4) $page["static"] = 1;
		$page["author"] = $this->selfUid;
		if ($page["public"]) $page["publish_date"] = time();

		$content = json_decode($page["content"], true);
		unset($page["content"]);

		if ($this->mysql->countRows("pages", "url", $page["url"])) {
			return array("urlAlreadyExists" => 1);
		}

		if (!$this->mysql->insertRow("pages", $page)) {
			return array("internalError" => 1);
		}

		$page = $this->mysql->selectRow("pages", "url", $page["url"]);
		$pageId = $page["id"];

		for ($i=0; count($content)>$i; $i++) {
			$contentData["page_id"] = $pageId;
			$contentData["position"] = $content[$i]["position"];
			$contentData["type"] = $content[$i]["type"];
			if (!$this->mysql->insertRow("contents", $contentData)) return array("internalError" => 1);
			$contentRow = $this->mysql->selectRow("contents", $contentData);

			switch ($content[$i]["type"]) {
				case "text":
					$textData["content_id"] = $contentRow["id"];
					$textData["content"] = $content[$i]["value"];
					if (!$this->mysql->insertRow("texts", $textData)) return array("internalError" => 1);
					break;
				default:
					
			}
		}
		return $pageId;
	}

	/**
	 * Editace stránky
	 */
	public function updatePage($pageId) {
		if (!$pageId) return array("pageIdUndefined" => 1);

		if(!$this->deletePage($pageId)) {
			return array("internalError" => 1);
		}
		return $this->createPage();
	}

	/**
	 * Načte obsah stránky podle URL nebo jejího ID
	 */
	public function loadPage($pageUrl = false, $pageId = false, $json = false) {
		$pageContent = array();

		if (!$pageUrl && !$pageId) return false;
		if ($pageUrl) {
			$page = $this->mysql->selectRow("pages", "url", $pageUrl);
		} else {
			if ($pageId) $page = $this->mysql->selectRow("pages", "id", $pageId);
		}
		if (!$page) return false;
		$contents = $this->mysql->getList(array("id", "type", "position"), "contents", array("page_id" => $page["id"]), array("position"));

		for ($i=0; count($contents)>$i; $i++) {
			$type = $contents[$i]["type"];
			switch ($type) {
				case "text":
					$text = $this->mysql->selectRow("texts", "content_id", $contents[$i]["id"]);
					break;
				default:
					
			}
			array_push(
				$pageContent,
				array(
					"type" => $type,
					"position" => $contents[$i]["position"],
					"value" => $text["content"]
				)
			);
		}
		if (!$json) return $pageContent;
		$page["content"] = json_encode($pageContent);
		return $page;
	}

	/**
	 * Načte seznam všech stránek
	 */
	public function getPageList() {
		return $this->mysql->getList(array("id", "language", "url", "public", "title"), "pages", false, array("title"));
		
	}
}
?>
