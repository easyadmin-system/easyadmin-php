<?php
class SysConfig
{
	/**
	 * Conscructor
	 **/
	function __construct($selfAuthority, $selfUid) {
		$this->selfAuthority = $selfAuthority;
		$this->selfUid = $selfUid;
	}

	/**
	 * Vrátí hodnoty z databáze
	 **/
	function getValues() {
		return MySQL::getList(array("variable", "value"), "sysconfig");
	}

	/**
	 * Uložení hodnot
	 **/
	function saveValues($keys) {
		$validData = FW::validPostData($keys);
		$err = false;
		foreach ($validData as $key => $val) {
			if (!MySQL::updateRow("sysconfig", "variable", $key, array("value" => $val))) {
				$err = true;
				return false;
			}
		}
		return true;
	}

	/**
	 * Načte konfigurační hodnotu z databáze
	 **/
	public static function getValue($key) {
		$val = MySQL::selectRow("sysconfig", "variable", $key);
		if ($val) return $val["value"];
		return false;
	}
}
?>
