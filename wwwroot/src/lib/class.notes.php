<?php
class Notes
{
	/**
	 * Conscructor
	 **/
	function __construct($mysql, $selfUid) {
		$this->mysql = $mysql;
		$this->selfUid = $selfUid;
	}

	/**
	 * Uloží poznámky
	 **/
	function save($notes) {
		$request = mysqli_query(
			$this->mysql->session,
			sprintf(
				"update %s_users set notes = '%s' where id like '%d'",
				Config::get("mysqlPrefix"),
				$notes,
				$this->selfUid
			)
		);

		if ($request) {
			return true;
		} else {
			Dbg::log("Error: Cannot save notes");
			return false;
		}
	}
}
?>
