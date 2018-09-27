<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Service\SerializerManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CartController
 * @package AppBundle\Controller
 *
 * @Route("cart")
 */
class CartController extends Controller
{
    /**
     * @return JsonResponse
     *
     * @Route("/")
     */
    public function viewAction(Request $request)
    {
        $cart = $this->get('cart.finder')->findCartForUserOrGuest($request, $this->getUser());
        
        if ($cart === null) {
            return new JsonResponse(['error' => 'No guest ID given or user found.'], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(SerializerManager::normalize($cart));
    }

    /**
     * @param Product $product
     * @param int $amount
     * @return JsonResponse
     *
     * @Route("/add", methods={"POST"})
     */
    public function addAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->getRepository('AppBundle:Product');

        return new JsonResponse([]);
    }
}