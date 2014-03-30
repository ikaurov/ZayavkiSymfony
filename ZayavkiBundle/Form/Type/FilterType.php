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
						'label' => 'Статус',				
					));	
					
		$builder->add('categoryid', 'choice', array(
						'attr' => array("class" => "filter_textClass", "value" => 2),
						'choices' => $options['data']['category'],
						'label' => 'Категория',				
					));	
					
		$builder->add('periodid', 'choice', array(
						'attr' => array("class" => "filter_textClass", "value" => 1),
						'choices' => $options['data']['period'],
						'label' => 'Дата',				
					));	
					
		$builder->add('dt1', 'text', array(
						'attr' => array("class" => "easyui-datebox", "style" => "width:138px"),
						'label' => 'Период',				
					));			

		$builder->add('dt2', 'text', array(
						'attr' => array("class" => "easyui-datebox", "style" => "width:138px"),
						'label' => 'Период',				
					));	
					
		$builder->add('search', 'text', array(
						'attr' =>  array("class" => "filter_textClass"),
						'label' => 'Период',				
					));	
					
		$builder->add('closed', 'checkbox', array(
						'attr' =>  array("class" => "filter_textClass"),
						'label' => 'Показывать закрытые',				
					));						
			
	}
	
	public function getName()
	{
		return 'filter';
	}
}
