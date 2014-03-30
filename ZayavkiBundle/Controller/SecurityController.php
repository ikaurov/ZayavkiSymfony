<?php
namespace Acme\ZayavkiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Acme\ZayavkiBundle\Model\Resanswer;

use Acme\ZayavkiBundle\Form\Type\LoginType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


class SecurityController extends Controller
{
/**
* login action	
*
* @return view
*/
	public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
		
		$data = array('login' => '');
		$form = $this->createForm(new LoginType(), $data);

        // collect login errors
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
           $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
        }
        // имя, введённое пользователем в последний раз
        return $this->render('AcmeZayavkiBundle:Security:login.html.twig', array(
			'form' => $form->createView(),
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $request,
        ));
    }	
}
