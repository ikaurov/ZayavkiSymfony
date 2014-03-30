<?php
namespace Acme\ZayavkiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Acme\ZayavkiBundle\Model\Resanswer;
use Acme\ZayavkiBundle\Form\Type\CategoryType;


class CategoryController extends Controller
{
/**
* categories	
*
* @return main view
*/
    public function indexAction()
    {
        return $this->render('AcmeZayavkiBundle:Category:index.html.twig');
    }
/**
* list of categories	
*
* @return json array of categories
*/
    public function dataAction()
    {
		$list = $this->getDoctrine()->getManager()->getRepository('AcmeZayavkiBundle:Category')->findCategoryTree();	
        return new Response(json_encode($list));
    }
/**
* create or edit category	
* @param int $id
* @return view
*/
	public function entityAction($id, Request $request)
    {							
		$entity = $this->getDoctrine()->getManager()->getRepository('AcmeZayavkiBundle:Category')->findEntity($id);
		$entity['choice'] = $this->getDoctrine()->getManager()->getRepository('AcmeZayavkiBundle:Category')->findGroupsOrderedByName('N');
		
		$form = $this->createForm(new CategoryType(), $entity);
			
		return $this->render('AcmeZayavkiBundle:Category:category.html.twig', array(
			'form' => $form->createView()
		));
    }
/**
* save category	
* @param int $id
* @return json array('id','message','success')
*/	
	public function saveAction($id, Request $request)
	{
		if ($request->getMethod() == 'POST') {
			
			$var = $request->request->all();
			
			$id = $this->getDoctrine()->getManager()->getRepository('AcmeZayavkiBundle:Category')
					->saveEntity( $id, $var['category']);
					
			return new Response(Resanswer::getRetJSON('',true, $id));	
		}	
	}

/**
* delete category (set deleted = 1)	
* @param int $id
* @return json array('id','message','success')
*/	
	public function deleteAction($id)
    {
		$this->getDoctrine()->getManager()->getRepository('AcmeZayavkiBundle:Category')->deleteEntity($id);		
		return new Response(Resanswer::getRetJSON('',true, $id));
    }	
}
