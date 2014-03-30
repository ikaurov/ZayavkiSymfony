<?php

namespace Acme\ZayavkiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Acme\ZayavkiBundle\Model\Resanswer;
use Acme\ZayavkiBundle\Form\Type\ProfType;


class ProfsController extends Controller
{
/**
* professions	
*
* @return main view
*/
    public function indexAction()
    {
        return $this->render('AcmeZayavkiBundle:Profs:index.html.twig');
    }
	
/**
* list of profs	
*
* @return json array of profs
*/
    public function dataAction()
    {
		$list = $this->getDoctrine()->getManager()->getRepository('AcmeZayavkiBundle:Profs')->findProfsList('','easyui');	
        return new Response(json_encode($list));
    }
/**
* create or edit profs	
* @param int id
* @return view
*/
	public function entityAction($id)
    {							
		$entity = $this->getDoctrine()->getManager()->getRepository('AcmeZayavkiBundle:Profs')->findEntity($id);
		
		$form = $this->createForm(new ProfType(), $entity);
			
		return $this->render('AcmeZayavkiBundle:Profs:profs.html.twig', array(
			'form' => $form->createView()
		));
    }
/**
* save profs	
* @param int id
* @return json array('id','message','success')
*/	
	public function saveAction($id, Request $request)
	{
		if ($request->getMethod() == 'POST') {
			
			$var = $request->request->all();
			
			$id = $this->getDoctrine()->getManager()->getRepository('AcmeZayavkiBundle:Profs')
					->saveEntity( $id, $var['profs']);
					
			return new Response(Resanswer::getRetJSON('',true, $id));	
		}	
	}
	
/**
* delete profs (set deleted = 1)	
* @param int id
* @return json array('id','message','success')
*/	
	public function deleteAction($id)
    {
		$this->getDoctrine()->getManager()->getRepository('AcmeZayavkiBundle:Profs')->deleteEntity($id);		
		return new Response(Resanswer::getRetJSON('',true, $id));
    }	

}
