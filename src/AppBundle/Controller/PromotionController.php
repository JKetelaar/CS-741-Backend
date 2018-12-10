<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Controller;

use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PromotionController
 * @package AppBundle\Controller
 *
 * @Route("/promotion")
 */
class PromotionController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/apply", methods={"POST"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="success"
     * )
     *
     * @SWG\Parameter(
     *     name="code",
     *     in="query",
     *     type="string",
     *     description="The promotion code to be applied"
     * )
     *
     * @SWG\Tag(name="promotion")
     */
    public function applyAction(Request $request)
    {
        $cart = $this->get('cart_helper')->findCartForUserOrGuest($request, $this->getUser());
        $code = $request->get('code');

        if ($cart === null) {
            return new JsonResponse(['error' => 'Cart required to apply promotion code'], Response::HTTP_BAD_REQUEST);
        }

        if ($code !== null) {
            $codeInstance = $this->getDoctrine()->getRepository('AppBundle:Promotion')->findOneBy(['code' => $code]);
            if ($codeInstance !== null) {
                $cart->setPromotion($codeInstance);

                $this->getDoctrine()->getManager()->persist($cart);
                $this->getDoctrine()->getManager()->flush();

                return new JsonResponse(['Promotion code applied']);
            }
        }

        return new JsonResponse(['error' => 'Invalid promotion code'], Response::HTTP_NOT_FOUND);
    }
}
