<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Purchase;
use AppBundle\Service\SerializerManager;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminPurchaseController
 * @package AppBundle\Controller\Admin
 *
 * @Route("admin/purchase")
 * @IsGranted("ROLE_ADMIN", statusCode=401, message="Method not allowed with current credentials")
 */
class AdminPurchaseController extends Controller
{
    /**
     * Lists all purchase entities.
     *
     * @Route("/", name="purchase_index", methods={"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns all purchases",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=AppBundle\Entity\Purchase::class))
     *     )
     * )
     * @SWG\Tag(name="purchase")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $purchases = $em->getRepository('AppBundle:Purchase')->findAll();

        return SerializerManager::normalizeAsJSONResponse($purchases);
    }

    /**
     * Finds and displays a purchase entity.
     *
     * @Route("/{id}", name="purchase_show", methods={"GET"})
     *
     * @param Purchase $purchase
     * @return Response
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the purchase of the given ID",
     *     @Model(type=AppBundle\Entity\Purchase::class))
     * )
     * @SWG\Tag(name="purchase")
     */
    public function showAction(Purchase $purchase)
    {
        return SerializerManager::normalizeAsJSONResponse($purchase);
    }
}