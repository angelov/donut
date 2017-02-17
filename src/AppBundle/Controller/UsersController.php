<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserRegistrationForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UsersController extends Controller
{
    /**
     * @Route("/register", name="app.users.register")
     */
    public function registerAction(Request $request)
    {
        $form = $this->createForm(UserRegistrationForm::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var User $user */
            $user = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Registration successfull!');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('users/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/users", name="app.users.index")
     */
    public function indexAction()
    {
        $users = $this->get('app.users.repository.default')->all();

        return $this->render('users/index.html.twig', [
            'users' => $users
        ]);
    }
}
