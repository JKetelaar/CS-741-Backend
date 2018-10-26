<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Listener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var AuthorizationChecker
     */
    private $authorizationChecker;

    /**
     * LoginSuccessHandler constructor.
     * @param Router $router
     * @param AuthorizationChecker $authorizationChecker
     */
    public function __construct(Router $router, AuthorizationChecker $authorizationChecker)
    {
        $this->router = $router;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @return null|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {

        $response = null;

        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $response = new RedirectResponse('http://localhost:4200/home');
        } else {
            if ($this->authorizationChecker->isGranted('ROLE_USER')) {
                $response = new RedirectResponse('http://localhost:4200/home');
            }
        }

        return $response;
    }
}