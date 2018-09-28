<?php

namespace AppBundle\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Class UserController
 * @package AppBundle\Controller\User
 *
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @return JsonResponse
     *
     * @Route("/login")
     */
    public function loginAction(Request $request)
    {
        $token = new UsernamePasswordToken('asd', 'asd', "secured_area", ['ROLE_USER']);

        $session = $this->get('session');
        $token = new UsernamePasswordToken('asd', null, 'main', []);
        $session->set('_security_main', serialize($token));
        $session->save();

        $this->get("security.token_storage")->setToken($token);
        $event = new InteractiveLoginEvent($request, $token);
        $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

        return new JsonResponse();
    }
}
