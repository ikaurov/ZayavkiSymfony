<?php

namespace Acme\ZayavkiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Acme\ZayavkiBundle\Entity\Tickets;
use Acme\ZayavkiBundle\Model\Resanswer;
use Acme\ZayavkiBundle\Model\CustomDateFormat;
use Acme\ZayavkiBundle\Form\Type\TicketType;
use Acme\ZayavkiBundle\Form\Type\TicketStateType;
use Acme\ZayavkiBundle\Form\Type\TicketDplanType;


class TicketsController extends Controller
{

/**
* List of tickets
*
*/	
public function dataAction($params, Request $request)
{
	$params = json_decode($params, true);
	$var = array();
	if ($request->getMethod() == 'POST') {		
		$var = $request->request->all();
	}
	
	$page = (isset($var['page'])  ? $var['page']-1 : "0");
	$rows = (isset($var['rows'])  ? $var['rows'] : "20");
	
	$user = $this->get('security.context')->getToken()->getUser();
	
	$env = array( 'sort'  => (isset($var['sort'])  ? $var['sort'] : ''),
				  'order' => (isset($var['order']) ? $var['order'] :''),
				  'offset'=> $page * $rows, 
				  'to'    => $rows,
				  'head'  => $this->getDoctrine()->getRepository('AcmeZayavkiBundle:User')->cntHeadUser($user->getId()), 
				  'userid'=> $user->getId()
				  );		
				 
   	$list = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tickets')->getTicketList( $params, $env );
	$list['sort']  = $var['sort'];
	$list['order'] = $var['order'];
	return new Response(json_encode($list));
}

/**
* open Ticket	
* 
* @param int tsg
* @param int id
* @return Workers form
*/
	public function entityAction($id)
    {

		$user = $this->get('security.context')->getToken()->getUser();
		
		$head = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:User')->cntHeadUser($user->getId());
		$userid = $user->getId(); 
		$translate = $this->get('transloc')->getTranslated();
		if (  $head > 0 ) {
			$tsgs = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tsginfo')->getTsgsList('no_head','N','hash', $translate);
		}
		else {
			$tsgs = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tsginfo')->findTsgsListForUser($userid, 'hash');
		}
	
		$entity  = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tickets')->findEntity( $id, $userid);
		$entity['tsgs']   = $tsgs;
		$entity['cats']   = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Category')->findCategoryListHash('N', $translate);	
		$entity['alerts'] = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Comprop')->findCompropListHash(3,  '', $translate);

		$entity['dplan_fmt'] = (strlen($entity['dplan']) > 9) ? CustomDateFormat::dateAnsiToRus($entity['dplan']) : '';
		$entity['translate'] = $this->get('transloc')->getTranslated('T');
		$form = $this->createForm(new TicketType(), $entity);
		
		$var = array('nr' 	   => $entity['nr'],
					'fio'	   => $entity['fio'],
					'account'  => $entity['account'],
					'address'  => $entity['address'],
					'debt' 	   => $entity['debt'],
					'subname'  => $entity['subname'],
					'id' 	   => $entity['id'],
					'workerid' => $entity['workerid'],
					'tsgid'    => $entity['tsgid'],	
					'dstart'   => CustomDateFormat::dateAnsiToRus($entity['dstart']),
					'creator'  => $entity['creator'],					
		);	
		
		return $this->render('AcmeZayavkiBundle:Ticket:ticket.html.twig', array(
			'form' => $form->createView(), 
			'data' => $var,
			'translate'=> $entity['translate'],
		));
	}
	
/**
* open Closed Ticket	
* 
* @param int tsg
* @param int id
* @return Workers form
*/
	public function closedAction($id)
    {

		$user = $this->get('security.context')->getToken()->getUser();
		
		$head = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:User')->cntHeadUser($user->getId());
		$userid = $user->getId(); 
		$entity  = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tickets')->findEntity( $id, $userid);
			
		return $this->render('AcmeZayavkiBundle:Ticket:ticketclosed.html.twig', array(
			 'translate' => $this->get('transloc')->getTranslated('T'),
			 'entity'    => $entity,
			 'comments'  => $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tickets')->getHistory( $id )			 
		));
	}
	

/**
* save ticket
*
* @param int id
* @return result array
*/	
	public function saveAction($id, Request $request)
	{
		if ($request->getMethod() == 'POST') {			
			$var = $request->request->all();
			
			$user = $this->get('security.context')->getToken()->getUser();
			$row  = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tickets')->saveEntity( $id, $user->getId(), $var['ticket']);
			
			if (strlen($var['ticket']['perenos']) > 0) {
			// date was changed
				$this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tickets')->addComment($id, $user->getId(), $var['ticket']['perenos'].'('.$var['ticket']['dplan'].')', 1);
			}
			
			return new Response(Resanswer::getRetJSON('Добавлена новая заявка № '.$row['nr'], true, $row['isnew']));			
		}	
		return new Response(Resanswer::getRetJSON('не пост ?', true, 0));
	}
/**
* Find appartment owner delete ticket	
*
* @param int $id
* @return Resanswer structure
*/	
	public function stateAction($id)
    {
		$user = $this->get('security.context')->getToken()->getUser();
		$entity  = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tickets')->findEntity( $id, $user->getId());
	
		$data = array(	'statusid' => $entity['substatusid'], 
						'status'   => $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Comprop')->findCompropListHash(2, '',$translate),
						'translate' => $this->get('transloc')->getTranslated('T'));
		$form = $this->createForm(new TicketStateType(), $data);
	
		return $this->render('AcmeZayavkiBundle:Ticket:ticketstate.html.twig', array(
			'form' => $form->createView(),
			'translate' => $data['translate']
		));
    }
/**
* Set ticket state
*
* @param int $id
* @return Resanswer structure
*/	
	public function setstateAction($id, Request $request)
	{
		if ($request->getMethod() == 'POST') {			
			$var = $request->request->all();

			$user = $this->get('security.context')->getToken()->getUser();
			$data = $var['ticketstate'];			
			$data['userid'] = $user->getId();
			$row  = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tickets')->setState( $id, $data);
			//[TODO] send mail

			return new Response(Resanswer::getRetJSON($row['status'], true, $id));			
		}	
		return new Response(Resanswer::getRetJSON('', true, 0));
	}	
/**
* Open Dplan edit form	
*
* @param string $dplan - date. format dd.mm.yyyy. If it's length less 10 symbols then propose current day
* @return form
*/	
	public function dplanAction($dplan)
    {
		$translate = $this->get('transloc')->getTranslated('T');
		$form = $this->createForm(new TicketDplanType(), array('translate' => $translate));
		return $this->render('AcmeZayavkiBundle:Ticket:ticketdplan.html.twig', array(
			'form' => $form->createView(), 
			'translate' => $translate,
			'dplan' => ((strlen($dplan) < 10) ? CustomDateFormat::dateAnsiToRus(date("Y-m-d")) : $dplan )
		));
    }	
	
/**
* Find appartment owner delete ticket	
* @param int $tsg - tsg number
* @param int $kv  - apartment nr
* @return json array with list of apartments
*/	
	public function finduserAction($tsg, $kv)
    {
		$res = $this->getDoctrine()->getManager()->getRepository('AcmeZayavkiBundle:Tickets')->findOwner($tsg, $kv);			
		return new Response(json_encode($res));
    }	
	
/**
* Find tickets history for userid exclude current ticket $id	
* @param int $userid - owner
* @param int $id - ticket id
* @return json array with list of tickets
*/	
	public function historyAction($userid, $id)
    {
		$res = $this->getDoctrine()->getManager()->getRepository('AcmeZayavkiBundle:Tickets')->findHistory($userid, $id);			
		return new Response(json_encode($res));
    }	
	
/**
* delete Ticket (set deleted = 1)	
* @param int id
* @return Resanswer structure
*/	
	public function deleteAction($id)
    {
		$this->getDoctrine()->getManager()->getRepository('AcmeZayavkiBundle:Tickets')->deleteEntity( $id);		
		return new Response(Resanswer::getRetJSON('',true, $id));
    }
}

?>