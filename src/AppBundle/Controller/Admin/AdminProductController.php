<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Controller\Admin;

use AppBundle\DataFixtures\ProductFixtures;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductImage;
use AppBundle\Form\ProductType;
use AppBundle\Service\SerializerManager;
use Faker\Factory;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminProductController
 * @package AppBundle\Controller\Admin
 *
 * @Route("admin/product")
 * @IsGranted("ROLE_ADMIN", statusCode=401, message="Method not allowed with current credentials")
 */
class AdminProductController extends Controller
{
    /**
     * Creates a new product entity.
     *
     * @Route("/new", name="product_new", methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @SWG\Response(
     *     response=200,
     *     description="Adds a new product",
     *     @Model(type=AppBundle\Entity\Product::class))
     * )
     * @SWG\Tag(name="products")
     */
    public function newAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $faker = Factory::create();
            $directory = $this->getParameter('upload_directory');
            $em = $this->getDoctrine()->getManager();

            $images = ProductFixtures::generateImagesArray($faker, $directory, 1);
            foreach ($images as $image) {
                $productImage = new ProductImage();
                $productImage->setProduct($product);
                $productImage->setFilename(basename($image));

                $em->persist($productImage);
            }

            $em->persist($product);
            $em->flush();

            return new JsonResponse(SerializerManager::normalize($product));
        } else {
            return new JsonResponse(
                [
                    'error' => 'Could not create product',
                    'errors' => $this->get('form_error_helper')->getFormErrors($form),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit", name="product_edit", methods={"POST"})
     *
     * @param Request $request
     * @param Product $product
     *
     * @return JsonResponse
     *
     * @SWG\Response(
     *     response=200,
     *     description="Edits a specific product based on the given ID",
     *     @Model(type=AppBundle\Entity\Product::class))
     * )
     * @SWG\Tag(name="products")
     */
    public function editAction(Request $request, Product $product)
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);

            $orderItems = $this->getDoctrine()->getRepository('AppBundle:OrderItem')->findBy(['product' => $product]);
            if ($orderItems != null && is_array($orderItems)) {
                foreach ($orderItems as $orderItem) {
                    if ($orderItem->getPurchase() === null) {
                        $orderItem->setPrice($product->getFinalPrice());
                        $em->persist($orderItem);
                    }
                }
            }
            $em->flush();

            return new JsonResponse(SerializerManager::normalize($product));
        } else {
            return new JsonResponse(
                [
                    'error' => 'Could not edit product',
                    'errors' => $this->get('form_error_helper')->getFormErrors($form),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Deletes a product entity.
     *
     * @Route("/{id}", name="product_delete", methods={"DELETE"})
     *
     * @param Request $request
     * @param Product $product
     *
     * @return JsonResponse
     *
     * @SWG\Response(
     *     response=200,
     *     description="Deletes a specific product based on the given ID",
     *     @Model(type=AppBundle\Entity\Product::class))
     * )
     * @SWG\Tag(name="products")
     */
    public function deleteAction(Request $request, Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $product->setActive(false);
        $em->persist($product);
        $em->flush();

        return new JsonResponse(['Product deleted']);
    }
}
