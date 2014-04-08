<?php 
namespace Acme\ZayavkiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CategoryType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{			
		$builder->add('parentid', 'choice', array(
						'attr' => array("class" => "category_textClass"),
						'choices' => $options['data']['choice'],
						'label' => $options['data']['translate']['column.group'],				
					));
		$builder->add('name', 'text', array(
						'label' => $options['data']['translate']['column.name'],
						'attr'  => array("class" => "category_textClass"),						
					));
					
	}
	
	public function getName()
	{
		return 'category';
	}
}
