<?php
class Articles
{
	/**
	 * Conscructor
	 */
	function Articles($selfAuthority, $selfUid) {
		if (!$selfAuthority) return false;
		if (!$selfUid) return false;
		$this->selfAuthority = $selfAuthority;
		$this->selfUid = $selfUid;
	}

	/**
	 * Smaže článek
	 */
	public function deleteArticle($articleId) {
		return MySQL::deleteRow("articles", "id", $articleId);
	}

	/**
	 * Vytvoří nový článek
	 */
	public function createArticle() {
		$err = array();

		$article = FW::validPostData(array(
			"title", "url", "content", "public", "meta_description",
			"keywords", "priority", "edit_frequency", "noindex"
		));
		if (!$article["title"]) $err["titleIsEmpty"] = 1;
		if (!$article["url"]) $err["urlIsEmpty"] = 1;
		$article["url"] = FW::parseRelativeUrl($article["url"]);
		$article["author"] = $this->selfUid;
		if ($article["public"]) $article["publish_date"] = time();

		$content = json_decode($article["content"], true);
		unset($article["content"]);

		if (MySQL::countRows("articles", "url", $article["url"])) {
			return array("urlAlreadyExists" => 1);
		}

		if (!MySQL::insertRow("articles", $article)) {
			return array("internalError" => 1);
		}

		$article = MySQL::selectRow("articles", "url", $article["url"]);
		$articleId = $article["id"];

		for ($i=0; count($content)>$i; $i++) {
			$contentData["article_id"] = $articleId;
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
		return $articleId;
	}

	/**
	 * Editace článku
	 */
	public function updateArticle($articleId) {
		if (!$articleId) return array("articleIdUndefined" => 1);

		if(!$this->deleteArticle($articleId)) {
			return array("internalError" => 1);
		}
		return $this->createArticle();
	}

	/**
	 * Načte obsah článku podle URL nebo jeho ID
	 */
	public function loadArticle($articleUrl = false, $articleId = false, $json = false) {
		$articleContent = array();

		if (!$articleUrl && !$articleId) return false;
		if ($articleUrl) {
			$article = MySQL::selectRow("articles", "url", $articleUrl);
		} else {
			if ($articleId) $article = MySQL::selectRow("articles", "id", $articleId);
		}
		if (!$article) return false;
		$contents = MySQL::getList(array("id", "type", "position"), "contents", array("article_id" => $article["id"]), array("position"));

		for ($i=0; count($contents)>$i; $i++) {
			$type = $contents[$i]["type"];
			switch ($type) {
				case "text":
					$text = MySQL::selectRow("texts", "content_id", $contents[$i]["id"]);
					break;
				default:
					
			}
			array_push(
				$articleContent,
				array(
					"type" => $type,
					"position" => $contents[$i]["position"],
					"value" => $text["content"]
				)
			);
		}
		if (!$json) return $articleContent;
		$article["content"] = json_encode($articleContent);
		return $article;
	}

	/**
	 * Načte seznam všech článků
	 */
	public function getArticles() {
		return MySQL::getList(array("id", "url", "public", "title"), "articles", false, array("title"));
		
	}
}
?>
