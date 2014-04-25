<?php
namespace Acme\ZayavkiBundle\Tests\Model;

class TestHelper
{
/**
@param str @str

@return bool
*/
	public function uiarray( $str )
	{
		$arr = json_decode($str, true);
		
		return is_array($arr);
	}
/**
@param str @res

@return array
*/	
	
	public function check_result( $res )
	{
		$arr = array('result' => false,
					 'index'  => -1,
				);
		$arr['result'] = is_array($res);
		if ($arr['result'] == false) {
			$res = json_decode($res, true);
			$arr['result'] = is_array($res);
		}
		$arr['id']     = $res['id'];
						 
		return $arr;
	}		
/**
@param client @client
@param str @url
@param int @id

@return array
*/	
	public function check_data($client, $url, $id) {
			$arr = array('result' => false,
						 'index'  => -1,
						 'item' => null,
						 );
	
	        $crawler = $client->request('GET', $url);
			
			$res = json_decode($client->getResponse()->getContent(), true);	
			
			$arr['result'] = is_array($res);
			if ($arr['result'] == false) {
				return $arr;
			}
			
			if ($id >= 0) {
				$i = 0;
				foreach($res['rows'] as $rs) {
					if ($rs['id'] == $id) {
						$arr['index'] = $i;
						$arr['item']  = $rs;
						$result = true;
						break;
					}	
					$i++;
				}
			}				
		
			return $arr;
	}
	
}