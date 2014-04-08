<?php 
namespace Acme\ZayavkiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TicketType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('id', 'text', array(
					'attr' => array("hidden" => "true"),
					'label' => 'id',				
		));	
		
		$builder->add('userid', 'text', array(
					'attr' => array("hidden" => "true"),
					'label' => '',				
		));	
		
		$builder->add('tsgid', 'choice', array(
					'attr' => array("class" => "ticket_text"),
					'choices' => $options['data']['tsgs'],
					'label' => $options['data']['translate']['ticket.company'],				
		));
	
		$builder->add('podal', 'text', array(
						'label' => $options['data']['translate']['ticket.podal'],
						'attr'  => array("class" => "ticket_text"),					
					));	
					
		$builder->add('categoryid', 'choice', array(
					'attr' => array("class" => "ticket_sel"),
					'choices' => $options['data']['cats'],
					'label' => $options['data']['translate']['ticket.category'],				
		));	

		$builder->add('alert', 'choice', array(
					'attr' => array("class" => "ticket_sel"),
					'choices' => $options['data']['alerts'],
					'label' => $options['data']['translate']['ticket.urgent'],				
		));						
	
		$builder->add('phone', 'text', array(
						'label' => $options['data']['translate']['ticket.phone'],
						'attr'  => array("class" => "ticket_col1"),				
					));	

		$builder->add('email', 'text', array(
						'label' => $options['data']['translate']['ticket.email'],
						'attr'  => array("class" => "ticket_col2"),				
					));
					
		$builder->add('preftime', 'text', array(
						'label' => $options['data']['translate']['ticket.preftime'],
						'attr'  => array("class" => "ticket_text"),					
					));					
	
		$builder->add('message', 'textarea', array(
						'label' => $options['data']['translate']['ticket.message'],
						'attr'  => array("class" => "ticket_textarea", "rows" => 3),						
					));
					
		$builder->add('workerid', 'text', array(
						'label' => $options['data']['translate']['ticket.charge'],
						'attr'  => array("class" => "ticket_col1"),					
					));	
					
					
		$builder->add('note', 'textarea', array(
						'label' => $options['data']['translate']['ticket.comment'],
						'attr'  => array("class" => "ticket_textarea", "rows" => 3),						
					));		

		$builder->add('dplan', 'text', array(
						'label' => $options['data']['translate']['ticket.dplan'],
						'attr'  => array("class" => "ticket_col1", 
										 'readonly' => 'readonly', 
										 'value' => ((strlen($options['data']['dplan_fmt']) > 9) ? $options['data']['dplan_fmt'] : '<'.$options['data']['translate']['ticket.nodate'].'>')),					
					));	

		$builder->add('perenos', 'text', array(
						'label' => '',
						'attr'  => array("hidden" => "true"),				
					));		

		$builder->add('tsgidold', 'text', array(
						'label' => '',
						'attr'  => array("hidden" => "true", "value" => $options['data']['tsgid'] ),				
					));						
	}
	
	public function getName()
	{
		return 'ticket';
	}
}
