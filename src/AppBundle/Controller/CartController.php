<?php

namespace AppBundle\Controller;

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
        $cart = $this->get('cart_helper')->findCartForUserOrGuest($request, $this->getUser());

        if ($cart === null) {
            return new JsonResponse(['error' => 'No guest ID given or user found.'], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(SerializerManager::normalize($cart));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/add", methods={"POST"})
     */
    public function addAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->getRepository('AppBundle:Product');
        $productManager = $entityManager->getRepository('AppBundle:Product');
        $product = $productManager->findOneBy(['id' => $request->get('product')]);

        if ($product === null) {
            return new JsonResponse(
                ['error' => 'Could not find a product with requested ID.'], Response::HTTP_NOT_FOUND
            );
        }

        $cart = $this->get('cart_helper')->findCartForUserOrGuest($request, $this->getUser());
        $this->get('cart_helper')->addProductToCart($cart, $product);

        return new JsonResponse(SerializerManager::normalize($cart));
    }
}
