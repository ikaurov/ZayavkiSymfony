<?php 
namespace Acme\ZayavkiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name', 'text', array(
						'label' => $options['data']['translate']['column.name'],
						'attr'  => array("class" => "Category_textClass"),						
					));							
	}
	
	public function getName()
	{
		return 'profs';
	}
}
