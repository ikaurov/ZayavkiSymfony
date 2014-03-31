<?php

namespace Acme\ZayavkiBundle\Model;

class CustomDateFormat
{
	/**
	
	*/
	static public function dateAnsiToRus($orig, $from='mysql', $to = 'rus')
	{
		$result = '';
		$today = getdate();
		$y = $today['year'];
		$d = $today['mday'];
		$m = $today['mon'];
		
		if (strlen($orig) > 9) {

			switch ($from) {
			case 'mysql':	$y = intval($orig[0].$orig[1].$orig[2].$orig[3]);
							$m = $orig[5].$orig[6];
							$d = $orig[8].$orig[9];
						break;
			case 'rus' :	$y = intval($orig[6].$orig[7].$orig[8].$orig[9]);
							$m = $orig[3].$orig[4];
							$d = $orig[0].$orig[1];						
						break;
			}
		}
		
		switch ($to) {
		case 'mysql':	$result = "{$y}-{$m}-{$d}"; 
					break;
		case 'rus' :	$result = "{$d}.{$m}.{$y}"; 						
					break;
		}		

		return $result;
	}	
	
	
}



?>