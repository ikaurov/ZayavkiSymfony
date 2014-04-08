<?php 
namespace Acme\ZayavkiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TsginfoType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('tsgname', 'text', array(
						'label' => $options['data']['translate']['column.name'],
						'attr'  => array("class" => "category_textClass", "readonly" => "readonly"),						
					));
					
		$builder->add('tsgcode', 'text', array(
						'label' => $options['data']['translate']['column.code'],
						'attr'  => array("readonly" => "readonly"),						
					));	

		$builder->add('lk', 'text', array(
						'label' => $options['data']['translate']['label.lk'],
						'attr' => array("hidden" => "true"),					
					));					
					
	}
	
	public function getName()
	{
		return 'tsg';
	}
}
