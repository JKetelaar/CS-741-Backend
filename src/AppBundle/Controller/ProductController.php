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
     *         @SWG\Items(ref=@Model(type=AppBundle\Entity\Product::class, groups={"default"}))
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
     * @SWG\Tag(name="products")
     */
    public function indexAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $products = $entityManager->getRepository('AppBundle:Product')->findBy([], [], ($limit = $request->get('limit')) !== null ? $limit : null);

        return new JsonResponse(SerializerManager::normalize($products, ['minimal']));
    }

    /**
     * Creates a new product entity.
     *
     * @Route("/new", name="product_new", methods={"POST"})
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @SWG\Response(
     *     response=200,
     *     description="Adds a new product",
     *     @Model(type=AppBundle\Entity\Product::class, groups={"default"}))
     * )
     * @SWG\Tag(name="products")
     */
    public function newAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm('AppBundle\Form\ProductType', $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_show', ['id' => $product->getId()]);
        }

        return $this->render(
            'product/new.html.twig',
            [
                'product' => $product,
                'form' => $form->createView(),
            ]
        );
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
     *     @Model(type=AppBundle\Entity\Product::class, groups={"default"}))
     * )
     * @SWG\Tag(name="products")
     */
    public function showAction(Product $product)
    {
        return new JsonResponse(SerializerManager::normalize($product));
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit", name="product_edit", methods={"POST"})
     *
     * @param Request $request
     * @param Product $product
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @SWG\Response(
     *     response=200,
     *     description="Edits a specific product based on the given ID",
     *     @Model(type=AppBundle\Entity\Product::class, groups={"default"}))
     * )
     * @SWG\Tag(name="products")
     */
    public function editAction(Request $request, Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);
        $editForm = $this->createForm('AppBundle\Form\ProductType', $product);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_edit', ['id' => $product->getId()]);
        }

        return $this->render(
            'product/edit.html.twig',
            [
                'product' => $product,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ]
        );
    }

    /**
     * Creates a form to delete a product entity.
     *
     * @param Product $product The product entity
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createDeleteForm(Product $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('product_delete', ['id' => $product->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Deletes a product entity.
     *
     * @Route("/{id}", name="product_delete", methods={"DELETE"})
     *
     * @param Request $request
     * @param Product $product
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @SWG\Response(
     *     response=200,
     *     description="Deletes a specific product based on the given ID",
     *     @Model(type=AppBundle\Entity\Product::class, groups={"default"}))
     * )
     * @SWG\Tag(name="products")
     */
    public function deleteAction(Request $request, Product $product)
    {
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
        }

        return $this->redirectToRoute('product_index');
    }
}
