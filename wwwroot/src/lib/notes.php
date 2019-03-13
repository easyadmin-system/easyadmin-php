<?php
class Notes
{
	/**
	 * Conscructor
	 **/
	function notes($selfUid) {
		$this->selfUid = $selfUid;
	}

	/**
	 * Uloží poznámky
	 **/
	function save($notes) {
		$request = mysql_query(
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
