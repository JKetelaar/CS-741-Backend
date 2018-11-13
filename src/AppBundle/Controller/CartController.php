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
     * @SWG\Parameter(
     *     name="quantity",
     *     in="query",
     *     type="integer",
     *     description="The quantity to be added to the cart; defaults to 1"
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
        $quantity = intval($request->get('quantity'));
        if ($quantity <= 0){
            $quantity = 1;
        }

        if ($product === null) {
            return new JsonResponse(
                ['error' => 'Could not find a product with requested ID.'], Response::HTTP_NOT_FOUND
            );
        }

        $cart = $this->get('cart_helper')->findCartForUserOrGuest($request, $this->getUser());

        if ($cart === null) {
            $cart = $this->get('cart_helper')->generateCart($request, $this->getUser());
        }

        $this->get('cart_helper')->addProductToCart($cart, $product, $quantity);

        return new JsonResponse(SerializerManager::normalize($cart));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/delete", methods={"DELETE"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Deletes an item from the cart of the current user",
     *     @Model(type=AppBundle\Entity\Cart::class))
     * )
     *
     * @SWG\Parameter(
     *     name="product",
     *     in="query",
     *     type="string",
     *     description="The product ID to be deleted from the cart"
     * )
     *
     * @SWG\Tag(name="cart")
     */
    public function deleteAction(Request $request)
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
            return new JsonResponse(
                ['error' => 'Could not find cart for user.'], Response::HTTP_NOT_FOUND
            );
        }

        $this->get('cart_helper')->removeProductFromCart($cart, $product);

        return new JsonResponse(SerializerManager::normalize($cart));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/adjust", methods={"PUT"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Adjusts the quantity of an item in the cart of the current user",
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
     *
     * @SWG\Parameter(
     *     name="quantity",
     *     in="query",
     *     type="integer",
     *     description="The quantity for the product in the cart"
     * )
     *
     * @SWG\Tag(name="cart")
     */
    public function adjustAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->getRepository('AppBundle:Product');
        $productManager = $entityManager->getRepository('AppBundle:Product');
        $product = $productManager->findOneBy(['id' => $request->get('product')]);
        $quantity = intval($request->get('quantity'));

        if ($quantity <= 0) {
            return new JsonResponse(
                ['error' => 'Invalid quantity given.'], Response::HTTP_BAD_REQUEST
            );
        }

        if ($product === null) {
            return new JsonResponse(
                ['error' => 'Could not find a product with requested ID.'], Response::HTTP_NOT_FOUND
            );
        }

        $cart = $this->get('cart_helper')->findCartForUserOrGuest($request, $this->getUser());

        if ($cart === null) {
            return new JsonResponse(
                ['error' => 'Could not find cart for user.'], Response::HTTP_NOT_FOUND
            );
        }

        $this->get('cart_helper')->setQuantityForProduct($cart, $product, $quantity);

        return new JsonResponse(SerializerManager::normalize($cart));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/clear", methods={"POST"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="success"
     * )
     *
     * @SWG\Tag(name="cart")
     */
    public function clearAction(Request $request)
    {
        $cart = $this->get('cart_helper')->findCartForUserOrGuest($request, $this->getUser());

        if ($cart !== null) {
            $this->get('cart_helper')->clearCart($cart);
        }

        return new JsonResponse(['Cleared cart']);
    }
}
