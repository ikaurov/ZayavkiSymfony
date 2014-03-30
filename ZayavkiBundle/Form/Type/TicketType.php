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
					'label' => 'Хозяин',				
		));	
		
		$builder->add('tsgid', 'choice', array(
					'attr' => array("class" => "ticket_text"),
					'choices' => $options['data']['tsgs'],
					'label' => 'Организация',				
		));
	
		$builder->add('podal', 'text', array(
						'label' => 'Заявку подал',
						'attr'  => array("class" => "ticket_text"),		//	style="width:473px;"			
					));	
					
		$builder->add('categoryid', 'choice', array(
					'attr' => array("class" => "ticket_sel"),
					'choices' => $options['data']['cats'],
					'label' => 'Категория',				
		));	

		$builder->add('alert', 'choice', array(
					'attr' => array("class" => "ticket_sel"),
					'choices' => $options['data']['alerts'],
					'label' => 'Срочность',				
		));						
	
		$builder->add('phone', 'text', array(
						'label' => 'Телефон',
						'attr'  => array("class" => "ticket_col1"),		//	style="width:473px;"			
					));	

		$builder->add('email', 'text', array(
						'label' => 'Эл. почта',
						'attr'  => array("class" => "ticket_col2"),		//	style="width:473px;"			
					));
					
		$builder->add('preftime', 'text', array(
						'label' => 'Время',
						'attr'  => array("class" => "ticket_text"),					
					));					
	
		$builder->add('message', 'textarea', array(
						'label' => 'Текст заявки',
						'attr'  => array("class" => "ticket_textarea", "rows" => 3),						
					));
					
		$builder->add('workerid', 'text', array(
						'label' => 'Ответственный',
						'attr'  => array("class" => "ticket_col1"),					
					));	
					
					
		$builder->add('note', 'textarea', array(
						'label' => 'Примечание',
						'attr'  => array("class" => "ticket_textarea", "rows" => 3),						
					));		

		$builder->add('dplan', 'text', array(
						'label' => 'Выполнить до',
						'attr'  => array("class" => "ticket_col1", 'readonly' => 'readonly', 'value' => $options['data']['dplan_fmt']),					
					));	

		$builder->add('perenos', 'text', array(
						'label' => '',
						'attr'  => array("hidden" => "true"),				
					));					



		  
					
	}
	
	public function getName()
	{
		return 'ticket';
	}
}
