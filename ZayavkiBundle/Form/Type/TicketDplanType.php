<?php 
namespace Acme\ZayavkiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TicketDplanType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
	
	
		$builder->add('dplan', 'text', array(
						'attr' => array( "style" => "width:138px"),//"class" => "easyui-datebox",
						'label' => 'Дата выполнения',				
					));	

		$builder->add('comment', 'text', array(
						'attr' => array("class" => "category_textClass"),
						'label' => 'Примечание',				
					));						
	}
	
	public function getName()
	{
		return 'ticketdplan';
	}
}
