<?php 
namespace Acme\ZayavkiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class WorkerType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('ownid', 'choice', array(
						'attr' => array("class" => "Category_textClass"),
						'choices' => $options['data']['tsgs'],
						'label' => 'Обслуживает',				
					));	
	
		$builder->add('profid', 'choice', array(
						'attr' => array("class" => "Category_textClass"),
						'choices' => $options['data']['profs'],
						'label' => 'Профессия',				
					));
					
		$builder->add('name', 'text', array(
						'label' => 'Название',
						'attr'  => array("class" => "Category_textClass"),						
					));
					
	}
	
	public function getName()
	{
		return 'worker';
	}
}
