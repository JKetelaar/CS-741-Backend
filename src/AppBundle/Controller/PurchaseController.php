<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Purchase;
use AppBundle\Form\PurchaseType;
use AppBundle\Service\SerializerManager;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
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
     *
     * @SWG\Response(
     *     response=200,
     *     description="Creates a new purchase",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=AppBundle\Entity\Purchase::class))
     *     )
     * )
     *
     * @SWG\Parameter(
     *     name="billingAddress['fullname']",
     *     in="formData",
     *     type="string",
     *     description="Billing address"
     * )
     *
     * @SWG\Parameter(
     *     name="shippingAddress",
     *     in="formData",
     *     type="string",
     *     description="Shipping address"
     * )
     *
     * @SWG\Post(consumes={"application/x-www-form-urlencoded"})
     *
     * @SWG\Tag(name="purchase")
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
            $em->persist($purchase->getShippingAddress());
            $em->persist($purchase->getBillingAddress());
            $em->persist($purchase);

            $em->flush();

            $this->get('cart_helper')->clearCart($cart);

            return SerializerManager::normalizeAsJSONResponse($purchase);
        }

        return new JsonResponse(
            ['error' => 'Could not create purchase', 'errors' => $this->get('form_error_helper')->getFormErrors($form)],
            Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * Completes purchase.
     *
     * @Route("/complete/{id}", name="purchase_complete", methods={"POST"})
     *
     * @param Request $request
     * @param Purchase $purchase
     * @return JsonResponse
     *
     * @SWG\Response(
     *     response=200,
     *     description="Creates a new purchase",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=AppBundle\Entity\Purchase::class))
     *     )
     * )
     *
     * @SWG\Tag(name="purchase")
     */
    public function completePurchase(Request $request, Purchase $purchase)
    {
        if ($purchase->getState() === Purchase::STATE_AWAITING_PAYMENT) {
            $purchase->setStateComplete();

            $em = $this->getDoctrine()->getManager();
            $em->persist($purchase);
            $em->flush();

            return SerializerManager::normalizeAsJSONResponse($purchase);
        } else {
            return new JsonResponse(
                [
                    'error' => 'Purchase already completed',
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
