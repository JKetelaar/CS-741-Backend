<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Controller;

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

        $purchase = $this->get('purchase_helper')->cartToPurchase($cart);

        $form = $this->createForm(PurchaseType::class, $purchase);
        $form->handleRequest($request);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($purchase);
            foreach ($purchase->getProducts() as $product) {
                $em->persist($product);
            }
            $em->flush();

            return SerializerManager::normalizeAsJSONResponse($purchase);
        }

        return new JsonResponse(
            ['error' => 'Could not create purchase', 'errors' => $this->get('form_error_helper')->getFormErrors($form)],
            Response::HTTP_BAD_REQUEST
        );
    }
}
