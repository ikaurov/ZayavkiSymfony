<?php

namespace Acme\ZayavkiBundle\Model;
use \Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Config\FileLocator;
		
class Transloc extends ContainerAware
{
	private $lang;
	private $trans;
	private $dict;
	
	public function __construct()
	{
		$this->dict = array();
	}
	
	public function getTranslated($sections = '', $force_lang = 'none')
	{		
		if ((isset($force_lang)) && ($force_lang != 'none')) 
		{
			if( !in_array($force_lang, array('ru', 'en')) ) {
				$force_lang = 'ru';
			}
			$this->lang = $force_lang;
		} else {
			$this->lang = ($this->container->hasParameter('app.language')) ? $this->container->getParameter('app.language') : 'ru';
		}
		
		$this->trans = array('language' => $this->lang);
		return $this->loadSpec($sections);
	}
	
	public function loadBasic()
	{
		foreach($this->dict as $key => $value) {
			if (isset($value[$this->lang])) {
				$this->trans[$key] = $value[$this->lang];
			}
		}		
		return $this;
	}
	
	public function loadSpec($area)
	{
		$this->initDict($area);
		
		foreach($this->dict as $key => $value) {
		    if  (isset($value[$this->lang]))  { //( (isset($value['area'])) && (substr_count($area, $value['area']) > 0) &&
				$this->trans[$key] = $value[$this->lang];
			}  
		}		
		return $this->trans;
	}	
	
	public function initDict($area)
	{
		$configDirectories = array(__DIR__.'/Dictionaries/');

		switch ($area) {
			case 'S': $filename = 'Filter.xml';
					  break;
			case 'A': $filename = 'Menus.xml';
					  break;
			case 'M': $filename = 'Mail.xml';
					  break;					  
			case 'P': $filename = 'Tickets.xml';
					  break;
			case 'T': $filename = 'Ticket.xml';
					  break;	
			case 'L': $filename = 'Login.xml';
					  break;	
			case 'B': $filename = 'Basic.xml';
					  break;					  
			default:
					  $filename = 'Basic.xml';				  
		}
		
		$locator = new FileLocator($configDirectories);
		$dfile = $locator->locate($filename, null, false);
		$xmlstring = file_get_contents($dfile[0]);
		$xml  = simplexml_load_string($xmlstring);
		$json = json_encode($xml);
		$this->dict = json_decode($json, TRUE);											
										
	}	

	
}