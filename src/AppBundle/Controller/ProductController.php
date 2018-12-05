<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Service\SerializerManager;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Product controller.
 *
 * @Route("product")
 */
class ProductController extends Controller
{
    /**
     * Lists all product entities.
     *
     * @Route("/", name="product_index", methods={"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns all available products",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=AppBundle\Entity\Product::class))
     *     )
     * )
     *
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="integer",
     *     description="The maximum amount of products to be shown"
     * )
     *
     * @SWG\Parameter(
     *     name="orderBy",
     *     in="query",
     *     type="string",
     *     description="The property in the Product class to order on"
     * )
     *
     * @SWG\Parameter(
     *     name="orderType",
     *     in="query",
     *     type="string",
     *     description="The way of ordering; either 'DESC' or 'ASC'"
     * )
     *
     * @SWG\Parameter(
     *     name="search",
     *     in="query",
     *     type="string",
     *     description="Search query to search in the name and description of the products"
     * )
     *
     * @SWG\Parameter(
     *     name="category",
     *     in="query",
     *     type="integer",
     *     description="Category ID to filter the products on"
     * )
     *
     * @SWG\Tag(name="products")
     */
    public function indexAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $products = $entityManager->getRepository('AppBundle:Product')->getProducts(
            $request->get('orderby'),
            $request->get('ordertype'),
            $request->get('limit'),
            $request->get('search'),
            $request->get('category')
        );

        return new JsonResponse(SerializerManager::normalize($products, ['minimal']));
    }

    /**
     * Finds and displays a product entity.
     *
     * @Route("/{id}", name="product_show", methods={"GET"})
     *
     * @param Product $product
     *
     * @return JsonResponse
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns a specific product based on the given ID",
     *     @Model(type=AppBundle\Entity\Product::class))
     * )
     * @SWG\Tag(name="products")
     */
    public function showAction(Product $product)
    {
        return new JsonResponse(SerializerManager::normalize($product));
    }
}
