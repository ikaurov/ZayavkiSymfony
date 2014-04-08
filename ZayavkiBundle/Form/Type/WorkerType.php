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
						'label' => $options['data']['translate']['caption.serves'],				
					));	
	
		$builder->add('profid', 'choice', array(
						'attr' => array("class" => "Category_textClass"),
						'choices' => $options['data']['profs'],
						'label' => $options['data']['translate']['column.profession'],				
					));
					
		$builder->add('name', 'text', array(
						'label' => $options['data']['translate']['column.name'],
						'attr'  => array("class" => "Category_textClass"),						
					));
					
	}
	
	public function getName()
	{
		return 'worker';
	}
}
