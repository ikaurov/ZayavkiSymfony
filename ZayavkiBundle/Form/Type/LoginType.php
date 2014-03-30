<?php 
namespace Acme\ZayavkiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('login', 'text', array(
						'label' => 'Пользователь',						
					));
					
		$builder->add('password', 'text', array(
						'label' => 'Пароль',						
					));					
					
	}
	
	public function getName()
	{
		return 'login';
	}
}
