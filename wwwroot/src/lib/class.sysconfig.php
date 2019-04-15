<?php
class SysConfig
{
	/**
	 * Conscructor
	 **/
	function __construct($mysql, $selfAuthority, $selfUid) {
		$this->mysql = $mysql;
		$this->selfAuthority = $selfAuthority;
		$this->selfUid = $selfUid;
	}

	/**
	 * Vrátí hodnoty z databáze
	 **/
	public function getValues() {
		return $this->mysql->getList(array("variable", "value"), "sysconfig");
	}

	/**
	 * Uložení hodnot
	 **/
	public function saveValues($keys) {
		$validData = FW::validPostData($keys);
		$err = false;
		foreach ($validData as $key => $val) {
			if (!$this->mysql->updateRow("sysconfig", "variable", $key, array("value" => $val))) {
				$err = true;
				return false;
			}
		}
		return true;
	}

	/**
	 * Načte konfigurační hodnotu z databáze
	 **/
	public function getValue($key) {
		$val = $this->mysql->selectRow("sysconfig", "variable", $key);
		if ($val) return $val["value"];
		return false;
	}
}
?>
