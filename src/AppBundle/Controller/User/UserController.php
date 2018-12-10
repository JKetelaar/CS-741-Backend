<?php

namespace AppBundle\Controller\User;

use AppBundle\Service\SerializerManager;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UserController
 * @package AppBundle\Controller\User
 *
 * @Route("users")
 */
class UserController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/login", name="user_login", methods={"POST"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Logs a user in, if the given information is correct"
     * )
     *
     * @SWG\Parameter(
     *     name="username",
     *     in="query",
     *     type="string",
     *     description="The username in the system, this should be the email address"
     * )
     *
     * @SWG\Parameter(
     *     name="password",
     *     in="query",
     *     type="string",
     *     description="The password of the user to login with"
     * )
     *
     * @SWG\Tag(name="users")
     */
    public function loginAction(Request $request)
    {
        $userName = $request->get('username');
        $password = $request->get('password');

        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findOneBy(['username' => $userName]);

        if (!$user) {
            return new JsonResponse(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

        $isValid = $this->get('security.password_encoder')
            ->isPasswordValid($user, $password);

        if (!$isValid) {
            return new JsonResponse(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->get('security.token_storage')->setToken($token);

        $this->get('session')->set('_security_main', serialize($token));

        $event = new InteractiveLoginEvent($request, $token);
        $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

        return new JsonResponse(['User logged in'], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     *
     * @Route("/register", name="user_register", methods={"POST"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Creates a new user in the system"
     * )
     *
     * @SWG\Parameter(
     *     name="email",
     *     in="query",
     *     type="string",
     *     description="The email of the user to register"
     * )
     *
     * @SWG\Parameter(
     *     name="password",
     *     in="query",
     *     type="string",
     *     description="The password of the user to register"
     * )
     *
     * @SWG\Parameter(
     *     name="password2",
     *     in="query",
     *     type="string",
     *     description="The password verification of the user to register, to make sure the user filled in the correct password"
     * )
     *
     * @SWG\Tag(name="users")
     */
    public function registerAction(Request $request, ValidatorInterface $validator)
    {
        $email = $request->get('email');
        $password = $request->get('password');
        $passwordVerification = $request->get('password2');

        if ($email !== null && !empty($email)) {
            $emailConstraint = new Email();
            $emailConstraint->message = 'Invalid email address';

            $errors = $validator->validate(
                $email,
                $emailConstraint
            );

            if (0 !== count($errors)) {
                $errorMessage = $errors[0]->getMessage();

                return new JsonResponse(['error' => $errorMessage], Response::HTTP_BAD_REQUEST);
            }
        } else {
            return new JsonResponse(['error' => 'Missing email address'], Response::HTTP_BAD_REQUEST);
        }

        if ($password !== null && !empty($password)) {
            $passwordConstraint = new Length(['min' => 8]);
            $passwordConstraint->minMessage = 'Password should be a minimum of 8 characters long';

            $errors = $validator->validate(
                $password,
                $passwordConstraint
            );

            if (0 !== count($errors)) {
                $errorMessage = $errors[0]->getMessage();

                return new JsonResponse(['error' => $errorMessage], Response::HTTP_BAD_REQUEST);
            }
        } else {
            return new JsonResponse(['error' => 'Missing password'], Response::HTTP_BAD_REQUEST);
        }

        if ($password !== $passwordVerification) {
            return new JsonResponse(['error' => 'Passwords do not match'], Response::HTTP_BAD_REQUEST);
        }

        $userManager = $this->get('fos_user.user_manager');

        if ($userManager->findUserByEmail($email) !== null) {
            return new JsonResponse(['error' => 'Email already exists'], Response::HTTP_BAD_REQUEST);
        }

        $user = $userManager->createUser();
        $user->setUsername($email);
        $user->setEmail($email);
        $user->setEmailCanonical($email);
        $user->setEnabled(1);
        $user->setPlainPassword($password);
        $user->setRoles(['ROLE_USER']);

        $userManager->updateUser($user);

        return new JsonResponse(['User created']);
    }


    /**
     * @return JsonResponse
     *
     * @Route("/current", name="current_user", methods={"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the current logged in user"
     * )
     *
     * @SWG\Tag(name="users")
     */
    public function currentAction()
    {
        return new JsonResponse(SerializerManager::normalize($this->getUser()));
    }
}