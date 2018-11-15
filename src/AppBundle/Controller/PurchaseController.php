<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Purchase;
use AppBundle\Form\PurchaseType;
use AppBundle\Service\SerializerManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Purchase controller.
 *
 * @Route("purchase")
 */
class PurchaseController extends Controller
{
    /**
     * Lists all purchase entities.
     *
     * @Route("/", name="purchase_index", methods={"GET"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $purchases = $em->getRepository('AppBundle:Purchase')->findAll();

        return SerializerManager::normalizeAsJSONResponse($purchases);
    }

    /**
     * Creates a new purchase entity.
     *
     * @Route("/new", name="purchase_new", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function newAction(Request $request)
    {
        $cart = $this->get('cart_helper')->findCartForUserOrGuest($request, $this->getUser());

        if ($cart === null) {
            return new JsonResponse(['error' => 'A cart is required to make a purchase'], Response::HTTP_BAD_REQUEST);
        }

        $purchase = new Purchase();

        $form = $this->createForm(PurchaseType::class, $purchase);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($purchase);
            $em->flush();

            return SerializerManager::normalizeAsJSONResponse($purchase);
        }

        return new JsonResponse(['error' => 'Could not create purchase'], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Finds and displays a purchase entity.
     *
     * @Route("/{id}", name="purchase_show", methods={"GET"})
     *
     * @param Purchase $purchase
     * @return Response
     */
    public function showAction(Purchase $purchase)
    {
        return SerializerManager::normalizeAsJSONResponse($purchase);
    }
}
