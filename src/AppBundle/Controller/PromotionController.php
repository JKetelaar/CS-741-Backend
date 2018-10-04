<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Controller;

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
     * @Route("/validate", methods={"POST"})
     */
    public function indexAction(Request $request)
    {
        $code = $request->get('code');
        if ($code !== null) {
            $codeInstance = $this->getDoctrine()->getRepository('AppBundle:Promotion')->findOneBy(['code' => $code]);
            if ($codeInstance !== null) {
                return new JsonResponse(['Valid promotion code']);
            }
        }

        return new JsonResponse(['error' => 'Invalid promotion code'], Response::HTTP_NOT_FOUND);
    }
}
