<?php
define("CONFIG", serialize($config));

/**
 * Načte konfiguraci a umožní k ní přístup odkudkoliv
 **/
class Config
{
	public static function get($key) {
		$config = unserialize(CONFIG);

		return $config[$key];
	}
}
?>
