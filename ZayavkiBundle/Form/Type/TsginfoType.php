<?php 
namespace Acme\ZayavkiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TsginfoType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('tsgname', 'text', array(
						'label' => 'Название',
						'attr'  => array("class" => "category_textClass", "readonly" => "readonly"),						
					));
					
		$builder->add('tsgcode', 'text', array(
						'label' => 'Код',
						'attr'  => array("readonly" => "readonly"),						
					));					
					
	}
	
	public function getName()
	{
		return 'tsg';
	}
}
