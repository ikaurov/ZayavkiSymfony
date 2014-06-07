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
	* access to language definer: $lang = ($this->container->hasParameter('app.language')) ? $this->container->getParameter('app.language') : 'ru';
	*/
	public function getUserArray(Request $request)
	{
		$result = array('id'   => 0, 
						'name' => '');
		$result['id']   = (int)$request->getSession()->get('userid');
		$result['name'] = $request->getSession()->get('username');
		if ($result['id'] == 0) {
			$user = $this->get('security.context')->getToken()->getUser();

			$request->getSession()->set('userid', $user->getId());
			$request->getSession()->set('username', $user->getUsername());
			
			$result['id']   = (int)$request->getSession()->get('userid');
			$result['name'] = $request->getSession()->get('username');
		
		}
		return $result;
	}
	
    public function indexAction(Request $request)
    {
		$res = $this->getUserArray( $request );

        return $this->render('AcmeZayavkiBundle:Default:index.html.twig', 
				array( 'prop_head' => $this->getDoctrine()->getRepository('AcmeZayavkiBundle:User')->cntHeadUser($res['id']),
					   'translate' => $this->get('transloc')->getTranslated('A'),
				));
    }
	/**
	* Title panel. User credentials
	*
	*/
    public function titleAction(Request $request)
    {
		$res = $this->getUserArray( $request );
		
		$list = current($this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tsginfo')->findTsgsListForUser($res['id'], 'array'));
		$name = $res['name'].(($list)? ' \ '.$list['name'] : '');

		
        return $this->render('AcmeZayavkiBundle:Default:title.html.twig', 
					array('name' => $name, 
					  	  'translate' => $this->get('transloc')->getTranslated(),
				));
    }
	/**
	* Tickets panel
	*
	*/
	public function ticketsAction(Request $request)
    {
		$res = $this->getUserArray( $request );
	
		//$user = $this->get('security.context')->getToken()->getUser();
        return $this->render('AcmeZayavkiBundle:Default:tickets.html.twig', 
					array('prop_head' => $this->getDoctrine()->getRepository('AcmeZayavkiBundle:User')->cntHeadUser($res['id']),
						  'userid'    => $res['id'],
						  'translate' => $this->get('transloc')->getTranslated('P'),
						  'colsize'   => $this->getDoctrine()->getRepository('AcmeZayavkiBundle:User')->getColumnsSize($res['id']),
					));
    }
	/**
	* Alert announce	
	*
	*/	
	public function alertsAction(Request $request)
    {
		$res = $this->getUserArray( $request );
		$list = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tickets')->getAlerts($res['id']);
	
        return new Response(json_encode($list));
    }
	/**
	* Basic filter Action
	*
	*/	
	public function fticketsAction(Request $request)
    {
		$res = $this->getUserArray( $request );

		$head = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:User')->cntHeadUser($res['id']);
		if ( $head > 0 ) {
			$tsgs = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tsginfo')->getTsgsList('no_head','A','array', $this->get('transloc')->getTranslated('B'));
		}
		else {
			$tsgs = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tsginfo')->findTsgsListForUser($res['id'],'array');
		}
		
        return $this->render('AcmeZayavkiBundle:Default:ftickets.html.twig', 
				array('tsgs' => $tsgs,
					  'translate' => $this->get('transloc')->getTranslated('S'),
				));
    }
/**
* Filter extended action
*
*/	
	public function filter_moreAction($params)
    {
		$apar = json_decode($params);
		
		$translate = $this->get('transloc')->getTranslated();
		
		$data = array( 'status'    => $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Comprop')->findCompropListHash(2,  'N', $translate),
					   'period'    => $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Comprop')->findCompropListHash(4,  'N', $translate),
					   'category'  => $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Category')->findCategoryListHash('N', $translate),
					   'translate' => $this->get('transloc')->getTranslated('S'),
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
		$translate = $this->get('transloc')->getTranslated('S');
		
		if ($apar->f_status > 0 ) {
			$row = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Comprop')->findEntityByUin(2, $apar->f_status);
			$message .= $translate['filter.status'].': <B>'.$row['name'].'</B>; ';
		}

		if ($apar->f_categs > 0 ) {
			$row = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Category')->findEntity($apar->f_categs);
			$message .= $translate['filter.category'].': <B>'.$row['name'].'</B>; ';
		}	

		if ($apar->f_cbperiod > 0 ) {
			$period = '<B>'.$apar->f_d1.' - '.$apar->f_d2.'</B>; ';
			switch ($apar->f_cbperiod) {
				case 1: $message .= $translate['filter.dstart'].': '.$period;
						break;
				case 2: $message .= $translate['filter.dplan'].': '.$period;
						break;
				case 3: $message .= $translate['filter.dstop'].': '.$period;
						break;
			}
		}

		$message .= '<B>'.(($apar->f_closed > 0) ? $translate['filter.inclclosed']: $translate['filter.onlyopen'] ).'</B>'.
					((strlen($apar->f_poisk) > 0)? $translate['filter.text'].':<B>"'.$apar->f_poisk.'"</B>; ':'');

        return new Response(json_encode(array('message' => $message, 'params' => $apar)));
    }		
	
	public function mailAction( $id, $msg) 
	{
		$data = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tickets')->getMailProperties($id);
		$data['info'] = $msg;
		
		$translate = $this->get('transloc')->getTranslated('M');
		
		$from = ($this->container->hasParameter('app.email')) ? $this->container->getParameter('app.email') : 'noreply@example.com';
		
		$message = \Swift_Message::newInstance()
			->setContentType("text/html")
			->setSubject($translate['mail.subject'].$data['nr'])
			->setFrom($from)
			->setTo('ikaurov@gmail.com') // $data['email']
			->setBody(	
				$this->renderView('AcmeZayavkiBundle:Default:mailtemplate.html.twig', array('data' => $data,
																							'translate' => $translate))
			);
		$this->get('mailer')->send($message);
	
		return new Response(Resanswer::getRetJSON('',true, 0));		
	}	
	
	/**
	* Title panel. User credentials
	*
	*/
    public function resizeAction($userid, $name, $width)
    {
		$this->getDoctrine()->getRepository('AcmeZayavkiBundle:User')->setColumnWidth($userid, $name, $width);

		return new Response(Resanswer::getRetJSON('',true, 0));
    }	
	
}
