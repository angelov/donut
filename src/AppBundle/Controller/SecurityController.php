<?php

namespace AppBundle\Controller;

use SocNet\Users\Form\LoginForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="security_login")
     */
    public function loginAction() : Response
    {
        $authUtils = $this->get('security.authentication_utils');

        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        $form = $this->createForm(LoginForm::class, [
            '_username' => $lastUsername
        ]);

        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
            'error' => $error
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logoutAction() : void
    {
        throw new \Exception('This should not be reached!');
    }
}
