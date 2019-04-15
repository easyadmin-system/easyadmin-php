<?php
class Contents
{
	/**
	 * Conscructor
	 */
	function __construct($selfAuthority, $selfUid) {
		if (!$selfAuthority) return false;
		if (!$selfUid) return false;
		$this->selfAuthority = $selfAuthority;
		$this->selfUid = $selfUid;
	}

	/**
	 * 
	 */
	public function getContent($contentId) {
		
	}
}
?>
