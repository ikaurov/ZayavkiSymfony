<?php

namespace Acme\ZayavkiBundle\Controller;

use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Acme\ZayavkiBundle\Entity\Operators;
use Acme\ZayavkiBundle\Model\Resanswer;
use Acme\ZayavkiBundle\Form\Type\OperatorType;


class OperatorsController extends Controller
{
/**
* categories	
*
* @return main view
*/
    public function indexAction()
    {
        return $this->render('AcmeZayavkiBundle:Operators:index.html.twig', array());
    }
/**
* list of categories	
* @param $view 0 - plain list, 
*              1- tree structure
* @return json array of categories
*/
    public function dataAction($tsg, Request $request)
    {
		$list = array("total" => 0, "rows" => array());
		
		$view = 1; // tree
		if ($request->getMethod() == 'POST') {		
			$var = $request->request->all();
			$view = $var['view'];		
		}				
		// base on view we load as tree for organizations or list for operators
		// as result we expect array 
		//   array('id' => entity id, 
		//		   'name' => name,
		//         'company' => 1/0 is it node or not 
		//         '_parentId' => if node);
				
		$stmt = $this->getDoctrine()->getEntityManager()->getConnection()->prepare('CALL Operators_tree(:tsg, :view );');
		$stmt->bindValue(':tsg', $tsg);
		$stmt->bindValue(':view', $view);

		$stmt->execute();
		$rows = $stmt->fetchAll();	

		$list["total"] = count($rows);
		foreach ($rows as $rs) {
			if ($view == 1) {
    	   	    $el = array('id'      => (($rs['title'] == 1)? 'A'.$rs['id']: $rs['id']), 
							'name'    => $rs['name'], 
							'company' => $rs['title'], 
							'state'   => 'closed');
        		if (  $rs['parentid'] > 0) {
        			$el['_parentId'] = 'A'.$rs['parentid'];
        			$el['state']     = 'open';
        		}
	       		$list['rows'][] = $el;				
			} 
			else {
				$list['rows'][] = array('id'      => $rs['id'], 
										'name'    => $rs['name'].' ('.$rs['more'].')', 
										'company' => 2);
			}
		}		

        return new Response(json_encode($list));
    }
/**
* create or edit Operators	
*
* @return Operators form
*/
	public function entityAction($id, $list, Request $request)
    {
		$rows = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tsginfo')->findBy( array(), array('head' => 'DESC', 'tsgname' => 'ASC'));
		$tsgs = array();
		foreach ($rows as $row) {
			$tsgs[] = array('id'   => $row->getTsgid(),
							'head' => $row->getHead(),
							'name' => $row->getTsgname());
		}
	
		$rows = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tsginfo')->findBy( array(), array('head' => 'DESC', 'tsgname' => 'ASC'));
		$orgs = array();
		foreach ($rows as $row) {
			$orgs[] = array('id'   => $row->getTsgid(),
							'head' => $row->getHead(),
							'name' => $row->getTsgname());
		}

		
		$data = array('name'     => '',
					  'login'    => '',
					  'password' => '',					  
					  'id'       => 0
				);	
	
		$entity = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Operators')->find($id);	
		if ($entity) {
			$data['name']    = $entity->getName();
			$data['login']   = $entity->getLogin();
			$data['password']= $entity->getPassword();
			$data['id']      = $entity->getId();
		}
		
		$form = $this->createForm(new OperatorType(), $data);
		
		if ($request->getMethod() == 'POST') {
			$form->handleRequest($request);
			
			$var = $request->request->all();
			
			$entity = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Operators')->find($id);	
			if (!$entity) {
				$entity = new Operators();
			}
			
			$entity->setName( $var['operator']['name'] ); 
			$entity->setLogin( $var['operator']['login']);
			$entity->setPassword( $var['operator']['password']);
				
			$em = $this->getDoctrine()->getManager();				
			$em->persist($entity);
			$em->flush();		
			
			// save a corresponding organizations
			$connection = $this->getDoctrine()->getEntityManager()->getConnection();
			
			$connection->executeUpdate('delete from Usertsg where userid = :userid', array('userid' => $entity->getId()));		
			$connection->executeUpdate("insert into Usertsg (userid, tsgid) select :userid, TSGId from TSGInfo where LOCATE(concat(';',TsgId,';'), :tlist) > 0", array('userid' => $entity->getId(), 'tlist' => $list));
		
			$res = new Resanswer(true, '', $entity->getId());
			return new Response($res->getRetJSON());
		}
			
		return $this->render('AcmeZayavkiBundle:Operators:operator.html.twig', array(
			'form' => $form->createView(), 'tsglist' => $tsgs, 'operator_id' => $id
		));
    }
/**
* organisations list for Operator	
*
* @return json array
*/	
	public function tsglistAction($id)
	{
		$list = array("total" => 0, "rows" => array());

		$stmt = $this->getDoctrine()->getEntityManager()->getConnection()->prepare('SELECT t.TSGName as name, t.TSGId as id, t.head FROM Usertsg u, TSGInfo t where u.tsgid = t.TSGId and u.userid = :id order by t.TSGName asc');
		$stmt->bindValue(':id', $id);
		$stmt->execute();
		$list['rows'] = $stmt->fetchAll();		
		$list['total'] = count($list['rows']); 		
		return new Response(json_encode($list));	
	}
	

/**
* delete Operators (set deleted = 1)	
*
* @return Resanswer structure
*/	
	public function deleteAction($id)
    {
		$entity = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Operators')->find($id);
		$entity->setDeleted(1);

		$em = $this->getDoctrine()->getManager();
		$em->persist($entity);
		$em->flush();
		
		$res = new Resanswer(true, '', $entity->getId());
		return new Response($res->getRetJSON());
    }	
}
