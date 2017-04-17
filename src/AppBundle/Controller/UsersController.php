<?php

namespace AppBundle\Controller;

use SocNet\Users\Commands\StoreUserCommand;
use SocNet\Users\User;
use SocNet\Users\Form\UserRegistrationForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    /**
     * @Route("/register", name="app.users.register")
     */
    public function registerAction(Request $request) : Response
    {
        $form = $this->createForm(UserRegistrationForm::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var StoreUserCommand $command */
            $command = $form->getData();

            $this->get('app.core.command_bus.default')->handle($command);

            $this->addFlash('success', 'Registration was successful. You many now login.');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('users/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/users", name="app.users.index")
     */
    public function indexAction() : Response
    {
        $users = $this->get('app.users.repository.default')->all();

        return $this->render('users/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/users/{id}", name="app.users.show", methods={"GET", "HEAD"})
     */
    public function showAction(User $user) : Response
    {
        return $this->render('users/show.html.twig', [
            'user' => $user
        ]);
    }
}
