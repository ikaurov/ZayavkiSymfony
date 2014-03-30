<?php

namespace Acme\ZayavkiBundle\Model;

class CustomDateFormat
{
	/**
	
	*/
	static public function dateAnsiToRus($orig)
	{
		$result = '';
		if (strlen($orig) > 9) {
			$y = intval($orig[0].$orig[1].$orig[2].$orig[3]);
			$m = $orig[5].$orig[6];
			$d = $orig[8].$orig[9];
		
			$result = "{$d}.{$m}.{$y}"; 
		}
		return $result;
	}	
	
	
}



?>