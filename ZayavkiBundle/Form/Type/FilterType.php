<?php 
namespace Acme\ZayavkiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FilterType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
	
	
		$builder->add('statusid', 'choice', array(
						'attr' => array("class" => "filter_textClass", "value" => 2),
						'choices' => $options['data']['status'],
						'label' => $options['data']['translate']['filter.status'],			
					));	
					
		$builder->add('categoryid', 'choice', array(
						'attr' => array("class" => "filter_textClass", "value" => 2),
						'choices' => $options['data']['category'],
						'label' => $options['data']['translate']['filter.category'],				
					));	
					
		$builder->add('periodid', 'choice', array(
						'attr' => array("class" => "filter_textClass", "value" => 1),
						'choices' => $options['data']['period'],
						'label' => $options['data']['translate']['filter.date'],					
					));	
					
		$builder->add('dt1', 'text', array(
						'attr' => array("class" => "easyui-datebox", "style" => "width:138px"),
						'label' => $options['data']['translate']['filter.period'],					
					));			

		$builder->add('dt2', 'text', array(
						'attr' => array("class" => "easyui-datebox", "style" => "width:138px"),
						'label' => $options['data']['translate']['filter.period'],					
					));	
					
		$builder->add('search', 'text', array(
						'attr' =>  array("class" => "filter_textClass"),
						'label' => $options['data']['translate']['filter.text'],				
					));	
					
		$builder->add('closed', 'checkbox', array(
//						'attr' =>  array("class" => "filter_textClass"),
						'label' => $options['data']['translate']['filter.closed'],				
					));						
			
	}
	
	public function getName()
	{
		return 'filter';
	}
}
