<?php
class Pages
{
	/**
	 * Conscructor
	 */
	function Pages($selfAuthority, $selfUid) {
		if (!$selfAuthority) return false;
		if (!$selfUid) return false;
		$this->selfAuthority = $selfAuthority;
		$this->selfUid = $selfUid;
	}

	/**
	 * Smaže stránku
	 */
	public function deletePage($pageId) {
		return MySQL::deleteRow("pages", "id", $pageId);
	}

	/**
	 * Vytviří novou stránku
	 */
	public function createPage() {
		$err = array();

		$page = FW::validPostData(array(
			"title", "url", "content", "public", "static", "meta_description",
			"keywords", "priority", "edit_frequency", "noindex"
		));
		if (!$page["title"]) $err["titleIsEmpty"] = 1;
		if (!$page["url"]) $err["urlIsEmpty"] = 1;
		$page["url"] = FW::parseRelativeUrl($page["url"]);
		if ($this->selfAuthority < 4) $page["static"] = 1;
		$page["author"] = $this->selfUid;
		if ($page["public"]) $page["publish_date"] = time();

		$content = json_decode($page["content"], true);
		unset($page["content"]);

		if (MySQL::countRows("pages", "url", $page["url"])) {
			return array("urlAlreadyExists" => 1);
		}

		if (!MySQL::insertRow("pages", $page)) {
			return array("internalError" => 1);
		}

		$page = MySQL::selectRow("pages", "url", $page["url"]);
		$pageId = $page["id"];

		for ($i=0; count($content)>$i; $i++) {
			$contentData["page_id"] = $pageId;
			$contentData["position"] = $content[$i]["position"];
			$contentData["type"] = $content[$i]["type"];
			if (!MySQL::insertRow("contents", $contentData)) return array("internalError" => 1);
			$contentRow = MySQL::selectRow("contents", $contentData);

			switch ($content[$i]["type"]) {
				case "text":
					$textData["content_id"] = $contentRow["id"];
					$textData["content"] = $content[$i]["value"];
					if (!MySQL::insertRow("texts", $textData)) return array("internalError" => 1);
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
			$page = MySQL::selectRow("pages", "url", $pageUrl);
		} else {
			if ($pageId) $page = MySQL::selectRow("pages", "id", $pageId);
		}
		if (!$page) return false;
		$contents = MySQL::getList(array("id", "type", "position"), "contents", array("page_id" => $page["id"]), array("position"));

		for ($i=0; count($contents)>$i; $i++) {
			$type = $contents[$i]["type"];
			switch ($type) {
				case "text":
					$text = MySQL::selectRow("texts", "content_id", $contents[$i]["id"]);
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
		return MySQL::getList(array("id", "url", "public", "title"), "pages", false, array("title"));
		
	}
}
?>
