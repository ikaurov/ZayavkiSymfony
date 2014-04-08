<?php
namespace Acme\ZayavkiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Acme\ZayavkiBundle\Entity\Workers;
use Acme\ZayavkiBundle\Model\Resanswer;
use Acme\ZayavkiBundle\Form\Type\WorkerType;

class WorkersController extends Controller
{
/**
* workers	
*
* @return main view
*/
    public function indexAction()
    {
		$id = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tsginfo')->getTsgHeadId();
        return $this->render('AcmeZayavkiBundle:Workers:index.html.twig', 
			array('tsgid' => $id,
				  'translate' => $this->get('transloc')->getTranslated('','en'), )
		);
    }
/**
* list of workers	
* @param int $tsg    0 - for head office or number specified company
* @param int $kind   0(default) - array "datagrid" style, 1 - simple array;
* @param String $options 
* @return json array of categories
*/
    public function dataAction($tsg, $kind, $incl, $options)
    {
		// if organization is not specified, then take with head = 1 ()
		$tsg = ($tsg == 0) ? $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tsginfo')->getTsgHeadId() : $tsg;
		
		$output = 'array';		
		if ($kind == 0) { 
			$output = 'easyui';
		}	
			
		$rows = $this->getDoctrine()->getManager()->getRepository('AcmeZayavkiBundle:Workers')->findWorkersForTsg($tsg, $options, $incl, $output);	
		return new Response(json_encode($rows));	
    }

/**
* create or edit Workers	
* 
* @param int tsg
* @param int id
* @return view
*/
	public function entityAction($tsg, $id)
    {
		// if organization is not specified, then take with head = 1 ()
		$tsg = ($tsg == 0) ? $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tsginfo')->getTsgHeadId() : $tsg;
	
		$entity = (array)$this->getDoctrine()->getManager()->getRepository('AcmeZayavkiBundle:Workers')->findEntity($id);
		$entity['translate'] = $this->get('transloc')->getTranslated('','en');
		
		$entity['ownid'] = ($entity['ownid'] == 0) ? $tsg : $entity['ownid'];
		$entity['profs'] = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Profs')->findProfsList('N', 'hash');
		$entity['tsgs']  = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tsginfo')->getTsgsList('all', '', 'hash');
		
		$form = $this->createForm(new WorkerType(), $entity);
		
		return $this->render('AcmeZayavkiBundle:Workers:workers.html.twig', array(
			'form' => $form->createView(),
			'translate' => $this->get('transloc')->getTranslated('','en') )
		);
	}			
	
/**
* save worker
*
* @param int tsg
* @param int id
* @return json array('id','message','success')
*/	
	public function saveAction($tsg, $id, Request $request)
	{
		if ($request->getMethod() == 'POST') {
			
			$var = $request->request->all();			
			$id = $this->getDoctrine()->getManager()->getRepository('AcmeZayavkiBundle:Workers')->saveEntity( $id, $var['worker']);
					
			return new Response(Resanswer::getRetJSON('',true, $id));				
		}	
	}	

/**
* delete Workers (set deleted = 1)	
* @param int id
* @return json array('id','message','success')
*/	
	public function deleteAction($id)
    {
		$this->getDoctrine()->getManager()->getRepository('AcmeZayavkiBundle:Workers')->deleteEntity($id);		
		return new Response(Resanswer::getRetJSON('',true, $id));
    }	
}
