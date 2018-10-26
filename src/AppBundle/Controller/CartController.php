<?php

namespace AppBundle\Controller;

use AppBundle\Service\SerializerManager;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
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
     * @Route("/", methods={"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the cart of the current user",
     *     @Model(type=AppBundle\Entity\Cart::class))
     * )
     * @SWG\Tag(name="cart")
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
     *
     * @SWG\Response(
     *     response=200,
     *     description="Adds an item to the cart of the current user, or appends 1 to the quantity of an item to the cart of the current user",
     *     @Model(type=AppBundle\Entity\Cart::class))
     * )
     *
     * @SWG\Parameter(
     *     name="product",
     *     in="query",
     *     type="string",
     *     description="The product ID to be added or appended to the cart"
     * )
     *
     * @SWG\Tag(name="cart")
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

        if ($cart === null) {
            $cart = $this->get('cart_helper')->generateCart($request, $this->getUser());
        }

        $this->get('cart_helper')->addProductToCart($cart, $product);

        return new JsonResponse(SerializerManager::normalize($cart));
    }
}
