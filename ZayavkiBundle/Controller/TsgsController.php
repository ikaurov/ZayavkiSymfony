<?php
namespace Acme\ZayavkiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Acme\ZayavkiBundle\Model\Resanswer;
use Acme\ZayavkiBundle\Form\Type\TsginfoType;

class TsgsController extends Controller
{
/**
* tsgs	
*
* @return main view
*/
    public function indexAction()
    {
        return $this->render('AcmeZayavkiBundle:Tsginfo:index.html.twig',
				array('translate' => $this->get('transloc')->getTranslated() ) );
    }
/**
* list of tsgs	
*
* @return json array of tsgs
*/
    public function dataAction()
    {	
		$list = $this->getDoctrine()->getManager()->getRepository('AcmeZayavkiBundle:Tsginfo')->getTsgsList('all','','array');	
        return new Response(json_encode($list));
    }
/**
* Tsginfo entity	
*
* @param int id
* @return view
*/	
	public function entityAction($id)
    {
		$entity = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tsginfo')->findEntity($id);	
		$entity['translate'] = $this->get('transloc')->getTranslated();
		
		$form = $this->createForm(new TsginfoType(), $entity);	
		
		return $this->render('AcmeZayavkiBundle:Tsginfo:tsgs.html.twig', array(
			'form' => $form->createView(),
			'translate' => $entity['translate'],
			'tsg_id' => $id, 
			'lk' => $entity['lk'] 
		));
    }	
/**
* List of operators for tsg. 
*
* @param int id
* @return json encoded array of operators
*/	
	public function oplistAction($id)
	{
		$list = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:User')->findUsersForTsg($id);	
        return new Response(json_encode($list));	
	}	
/**
* List of workers for tsg. 	
*
* @param int id
* @return json encoded array of workers
*/	
	public function worklistAction($id)
	{   
		$list = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Workers')->findWorkersForTsg($id, '', -1, 'easyui');	
        return new Response(json_encode($list));	
	}	
/**
* Set lk to On/Off	
*
* @param int id
* @param int lk
* @return json array('id','message','success')
*/	
	public function setlkAction($id, $lk)
	{
		$this->getDoctrine()->getManager()->getRepository('AcmeZayavkiBundle:Tsginfo')->setLkforTsg($id, $lk);		
		return new Response(Resanswer::getRetJSON('',true, $id));		
	}	
}
