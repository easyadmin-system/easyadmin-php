<?php
class Dict {
	public static function authority($i) {
		$v = array(
			"Běžný uživatel (0)",
			"Redaktor (1)",
			"Administrátor (2)",
			"Hlavní administrátor (3)",
			"Vývoj (4)",
			"Vývoj (5)"
		);
		echo $v[$i];
	}

	public static function pagePriority($i) {
		$v = array("0","0.1","0.2","0.3","0.4","0.5","0.6","0.7","0.8","0.9","1.0");
		return $v[$i];
	}

	public static function changeFreq($i) {
		$v = array(
			array("value" => "always", "title" => "neustále"),
			array("value" => "hourly", "title" => "každou hodinu"),
			array("value" => "daily", "title" => "každý den"),
			array("value" => "weekly", "title" => "každý týden"),
			array("value" => "monthly", "title" => "každý měsíc"),
			array("value" => "yearly", "title" => "každý rok"),
			array("value" => "never", "title" => "nikdy")
		);
		return $v[$i];
	}

	public static function helper($html) {
		echo '<span class="tooltip"><a><img src="/img/ico/ico-help.png" alt="help" /></a><span class="msg">'.$html.'</span></span>';
	}

	public static function short($txt, $len) {
		if (strlen($txt) > $len) return substr($txt, 0, $len-1) . "&hellip;";
		return $txt;
	}
}
?>
