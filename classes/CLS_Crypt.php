<?php 

	class CLS_Crypt
	{
		private $iv = 'f4dFblXq7L543k10'; #Same as in JAVA 


		function __construct()
		{
		}

		public static function encrypt($input) {
		$input = self::pkcs5_pad($input, mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB));
		$td    = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
		mcrypt_generic_init($td,$iv,mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND));
		$input = mcrypt_generic($td,$input);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		//return base64_encode($input);
		return bin2hex($input);
	}

	private static function pkcs5_pad($text, $blocksize){
		$pad = $blocksize - (strlen($text) % $blocksize);
		return $text . str_repeat(chr($pad), $pad);
	}

	public static function decrypt($sStr){
		//$decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128,$sKey,base64_decode($sStr),MCRYPT_MODE_ECB);
		$decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128,$iv,hex2bin($sStr),MCRYPT_MODE_ECB);
		return substr($decrypted, 0, -ord($decrypted[strlen($decrypted)-1]));
	}
	
	protected function hex2bin($hexdata) {
		  $bindata = '';

		  for ($i = 0; $i < strlen($hexdata); $i += 2) {
			$bindata .= chr(hexdec(substr($hexdata, $i, 2)));
		  }

		  return $bindata;
		}
	}
?>