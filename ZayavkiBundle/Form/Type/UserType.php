<?php 
namespace Acme\ZayavkiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name', 'text', array(
						'label' => 'Название',
						'attr'  => array("class" => "category_textClass"),						
					));
					
		$builder->add('username', 'text', array(
						'label' => 'Пользователь',
						'attr'  => array("class" => "category_textClass"),						
					));

		$builder->add('password', 'text', array(
						'label' => 'Пароль',
						'attr'  => array("class" => "category_textClass"),						
					));	
			
	}
	
	public function getName()
	{
		return 'user';
	}
}
