<?php 
namespace Acme\ZayavkiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('login', 'text', array(
						'label' => $options['data']['translate']['login.label.user'],						
					));
					
		$builder->add('password', 'text', array(
						'label' => $options['data']['translate']['login.label.password'],								
					));					
					
	}
	
	public function getName()
	{
		return 'login';
	}
}
