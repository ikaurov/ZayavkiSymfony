<?php
namespace Acme\ZayavkiBundle\Controller;

use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Acme\ZayavkiBundle\Model\Resanswer;
use Acme\ZayavkiBundle\Form\Type\UserType;


class UserController extends Controller
{
/**
* users
*
* @return main view
*/
    public function indexAction()
    {
        return $this->render('AcmeZayavkiBundle:User:index.html.twig', 
								array('tsgid' => $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tsginfo')->getTsgHeadId()));
    }
/**
* list of Users	
* @param $tsg
* @return json array of categories
*/
    public function dataAction($tsg, Request $request)
    {		
		$view = 1; // tree
		if ($request->getMethod() == 'POST') {		
			$var = $request->request->all();
			$view = $var['view'];		
		}				
		$list = array("total" => 0, "rows" => array());
		if ($view == 1) {
			$list['rows'] = $this->getDoctrine()->getManager()->getRepository('AcmeZayavkiBundle:User')->findUsersTree($tsg);	
		} else {
			$list['rows'] = $this->getDoctrine()->getManager()->getRepository('AcmeZayavkiBundle:User')->findUsersList($tsg);	
		}
		$list["total"] = count($list['rows']);	

        return new Response(json_encode($list));
    }
/**
* create or edit User	
* @param int id 
* @return view
*/
	public function entityAction($id)
    {
	
		$tsgs = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tsginfo')->getTsgsList('all', '', 'array');
		
		$entity = $this->getDoctrine()->getManager()->getRepository('AcmeZayavkiBundle:User')->findEntity($id);		
		$form = $this->createForm(new UserType(), $entity);
					
		return $this->render('AcmeZayavkiBundle:User:user.html.twig', array(
			'form' => $form->createView(), 'tsglist' => $tsgs, 'user_id' => $id
		));
    }
/**
* save User
* @param int id 
* @param string list 
* @return json array('id','message','success')
*/		
	public function saveAction($id, $list, Request $request)
	{
		if ($request->getMethod() == 'POST') {
			
			$var = $request->request->all();
			$id = $this->getDoctrine()->getManager()->getRepository('AcmeZayavkiBundle:User')->saveEntity( $id, $var['user']);

			// save a corresponding organizations
			$this->getDoctrine()->getManager()->getRepository('AcmeZayavkiBundle:User')->setTsgs( $id, $list);

			return new Response(Resanswer::getRetJSON('',true, $id));	
		}
	
	}
	
/**
* organisations list for User	
* @param int tsgid 
* @return json array
*/	
	public function tsglistAction($userid)
	{
		$list = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tsginfo')->findTsgsListForUser($userid, 'array');		
		return new Response(json_encode($list));	
	}

/**
* delete User (set deleted = 1)	
* @param int id
* @return json array('id','message','success')
*/	
	public function deleteAction($id)
    {
		$this->getDoctrine()->getManager()->getRepository('AcmeZayavkiBundle:User')->deleteEntity($id);	
		return new Response(Resanswer::getRetJSON('',true, $id));
    }	
}
