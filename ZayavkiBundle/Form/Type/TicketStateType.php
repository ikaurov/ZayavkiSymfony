<?php 
namespace Acme\ZayavkiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TicketStateType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
	
		$builder->add('statusid', 'choice', array(
						'attr' => array("class" => "filter_textClass"),
						'choices' => $options['data']['status'],
						'label' => $options['data']['translate']['ticket.label.status'],				
					));		

		$builder->add('comment', 'text', array(
						'attr' => array("class" => "category_textClass"),
						'label' => $options['data']['translate']['ticket.label.comment'],			
					));						
	}
	
	public function getName()
	{
		return 'ticketstate';
	}
}
