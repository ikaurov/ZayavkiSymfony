<?php

namespace Acme\ZayavkiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Acme\ZayavkiBundle\Model\Resanswer;
use Acme\ZayavkiBundle\Form\Type\FilterType;

class DefaultController extends Controller
{
	/**
	* Main entrance
	*
	*/
    public function indexAction()
    {
		$user = $this->get('security.context')->getToken()->getUser();
        return $this->render('AcmeZayavkiBundle:Default:index.html.twig', array('prop_head' => $this->getDoctrine()->getRepository('AcmeZayavkiBundle:User')->cntHeadUser($user->getId())));
    }
	/**
	* Title panel. User credentials
	*
	*/
    public function titleAction()
    {
		$user = $this->get('security.context')->getToken()->getUser();
		$name = $user->getName();
		$list = current($this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tsginfo')->findTsgsListForUser($user->getId(), 'array'));
		if ($list) {
			$name .= ' \ '.$list['name'];
		}

        return $this->render('AcmeZayavkiBundle:Default:title.html.twig', array('name' => $name));
    }
	/**
	* Tickets panel
	*
	*/
	public function ticketsAction()
    {
		$user = $this->get('security.context')->getToken()->getUser();
        return $this->render('AcmeZayavkiBundle:Default:tickets.html.twig', array('prop_head' => $this->getDoctrine()->getRepository('AcmeZayavkiBundle:User')->cntHeadUser($user->getId())));
    }
	/**
	* Alert announce	
	*
	*/	
	public function alertsAction()
    {
		$user = $this->get('security.context')->getToken()->getUser();
		$list = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tickets')->getAlerts($user->getId());
	
        return new Response(json_encode($list));
    }
	/**
	* Basic filter Action
	*
	*/	
	public function fticketsAction()
    {
		$user = $this->get('security.context')->getToken()->getUser();
		
		$head = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:User')->cntHeadUser($user->getId());
		if ( $head > 0 ) {
			$tsgs = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tsginfo')->getTsgsList('no_head','A','array');
		}
		else {
			$tsgs = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tsginfo')->findTsgsListForUser($user->getId(),'array');
		}
		
        return $this->render('AcmeZayavkiBundle:Default:ftickets.html.twig', array('tsgs' => $tsgs));
    }
/**
* Filter extended action
*
*/	
	public function filter_moreAction($params)
    {
		$apar = json_decode($params);
			
		$data = array( 'status'  => $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Comprop')->findCompropListHash(2,  'N'),
					   'period'  => $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Comprop')->findCompropListHash(4,  'N'),
					   'category'=> $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Category')->findCategoryListHash('N'),
		);
		$form = $this->createForm(new FilterType(), $data);
	
		return $this->render('AcmeZayavkiBundle:Default:fsearch.html.twig', array(
			'form' => $form->createView(),'params' => $apar,
		));
    }	
/**
* Filter submit action
* @return json array('id','message','success')
*/	
	public function filter_actAction($params, Request $request)
	{
		if ($request->getMethod() == 'POST') {
			
			$apar = json_decode($params);
			$var = $request->request->all();
			
			return new Response(Resanswer::getRetJSON('',true, 0));			
		}		
	}
	
	/**
	*
	*
	*/	
	public function filter_infoAction($params)
    {	
		$apar = json_decode($params);
		$message = '';
		
		if ($apar->f_status > 0 ) {
			$row = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Comprop')->findEntityByUin(2, $apar->f_status);
			$message .= 'Статус: <B>'.$row['name'].'</B>; ';
		}

		if ($apar->f_categs > 0 ) {
			$row = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Category')->findEntity($apar->f_categs);
			$message .= 'Категория: <B>'.$row['name'].'</B>; ';
		}	

		if ($apar->f_cbperiod > 0 ) {
			$period = '<B>'.$apar->f_d1.' - '.$apar->f_d2.'</B>; ';
			switch ($apar->f_cbperiod) {
				case 1: $message .= 'Период подачи заявки: '.$period;
						break;
				case 2: $message .= 'Период передачи в работу: '.$period;
						break;
				case 3: $message .= 'Период закрытия: '.$period;
						break;
			}
		}

		$message .= (($apar->f_closed > 0) ? '<B>Включая закрытые заявки</B>; ' : '<B>Только открытые заявки</B>; ').
					((strlen($apar->f_poisk) > 0)?'Поиск текста:<B>"'.$apar->f_poisk.'"</B>; ':'');

        return new Response(json_encode(array('message' => $message, 'params' => $apar)));
    }		
	
	public function mailAction( $id, $msg) 
	{
		$data = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tickets')->getMailProperties($id);
		$data['info'] = $msg;
		
		$message = \Swift_Message::newInstance()
			->setSubject('Уведомление об изменении в заявке № '.$data['nr'])
			->setFrom('cosmoservice@cosmoservice.spb.ru')
			->setTo('ikaurov@gmail.com')
			->setBody(	
				$this->renderView('AcmeZayavkiBundle:Default:mailtemplate.html.twig', array('data' => $data))
			);
		$this->get('mailer')->send($message);
	
		return new Response(Resanswer::getRetJSON('',true, 0));		
	}	
	
}
