<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Service\SerializerManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function viewAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $cartRepository = $entityManager->getRepository('AppBundle:Cart');

        // Fake data
        $cart = $cartRepository->findOneBy(['guestId' => 1]);

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
