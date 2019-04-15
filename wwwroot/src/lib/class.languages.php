<?php
class Languages
{
	/**
	 * Conscructor
	 */
	function __construct($mysql, $selfAuthority) {
		$this->mysql = $mysql;
		$this->selfAuthority = $selfAuthority;

		# Úroveň globálního oprávnění, které je potřeba pro všechny úkony ve skupinách
		$this->requiredAuth = 3;
	}

	/**
	 * Přidání nového jazyka
	 */
	function addLanguage($code) {
		if ($this->selfAuthority < $this->requiredAuth) return array("insufficientPermissions" => 1);

		if (!$code) $err["empty"] = 1;
		if ($this->langExists($code)) $err["exists"] = 1;

		if (sizeof($err)) {
			return $err;
		} else {
			if ($this->mysql->insertRow("languages", array("code" => $code))) {
				return true;
			}
			Dbg::log("Error: Cannot add new language");
			return false;
		}
	}

	/**
	 * Odebrání jazyka
	 */
	function removeLanguage($code) {
		if ($this->selfAuthority < $this->requiredAuth) return array("insufficientPermissions" => 1);
		if (!$this->langExists($code)) return array("doesNotExist" => 1);
		return $this->mysql->deleteRow("languages", "code", $code);
	}

	/**
	 * Zjistíme, zda-li je již jatyk používán
	 */
	function langExists($code) {
		return $this->mysql->countRows("languages", "code", $code);
	}

	/**
	 * Načtení abecedního seznamu aktivních jazyků
	 */
	function getActiveLanguages() {
		$languages = $this->mysql->getList(array("code"), "languages", false, array("code"));
		if ($languages) return $languages;
		return false;
	}

	/**
	 * Vrátí seznam dostupných jazyků
	 */
	function getAvailableLanguages() {
		$languages = Config::get("availableLanguages");
		if ($languages) return $languages;
		return false;
	}
}
?>
