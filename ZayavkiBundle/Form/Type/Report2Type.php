<?php 
namespace Acme\ZayavkiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class Report2Type extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
				
		$builder->add('dt1', 'text', array(
						'attr' => array("class" => "easyui-datebox", "style" => "width:138px"),
						'label' => $options['data']['translate']['filter.period'],					
					));			

		$builder->add('dt2', 'text', array(
						'attr' => array("class" => "easyui-datebox", "style" => "width:138px"),
						'label' => $options['data']['translate']['filter.period'],					
					));						
			
	}
	
	public function getName()
	{
		return 'report2';
	}
}
