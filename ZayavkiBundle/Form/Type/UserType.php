<?php 
namespace Acme\ZayavkiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name', 'text', array(
						'label' => $options['data']['translate']['column.name'],
						'attr'  => array("class" => "category_textClass"),						
					));
					
		$builder->add('username', 'text', array(
						'label' => $options['data']['translate']['column.user'],
						'attr'  => array("class" => "category_textClass"),						
					));

		$builder->add('password', 'text', array(
						'label' => $options['data']['translate']['column.password'],
						'attr'  => array("class" => "category_textClass"),						
					));	
			
	}
	
	public function getName()
	{
		return 'user';
	}
}
