<?php

namespace AppBundle\Security;

use AppBundle\Exceptions\ResourceNotFoundException;
use AppBundle\Form\LoginForm;
use SocNet\Users\Repositories\UsersRepositoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    private $formFactory;
    private $users;
    private $router;
    private $passwordEncoder;

    public function __construct(
        FormFactoryInterface $formFactory,
        UsersRepositoryInterface $users,
        RouterInterface $router,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->passwordEncoder = $passwordEncoder;
        $this->users = $users;
    }

    protected function getLoginUrl() : string
    {
        return $this->router->generate('security_login');
    }

    public function getCredentials(Request $request)
    {
        $isLoginSubmit = $request->getPathInfo() === '/login' && $request->isMethod('POST');

        if (!$isLoginSubmit) {
            return null;
        }

        $form = $this->formFactory->create(LoginForm::class);
        $form->handleRequest($request);

        $data = $form->getData();

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $data['_username']
        );

        return $data;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $email = $credentials['_username'];

        if ($email === null) {
            throw new CustomUserMessageAuthenticationException('Invalid credentials.');
        }

        try {
            return $this->users->findByEmail($email);
        } catch (ResourceNotFoundException $e) {
            throw new CustomUserMessageAuthenticationException('Invalid credentials.');
        }
    }

    public function checkCredentials($credentials, UserInterface $user) : bool
    {
        $password = $credentials['_password'];

        return $this->passwordEncoder->isPasswordValid($user, $password);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $homepage = $this->router->generate('homepage');

        return new RedirectResponse($homepage);
    }
}
