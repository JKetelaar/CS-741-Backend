<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Purchase;
use AppBundle\Form\PurchaseType;
use AppBundle\Service\SerializerManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @Route("/", name="purchase_index")
     * @Method("GET")
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
     * @Route("/new", name="purchase_new")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function newAction(Request $request)
    {
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
     * @Route("/{id}", name="purchase_show")
     * @Method("GET")
     *
     * @param Purchase $purchase
     * @return Response
     */
    public function showAction(Purchase $purchase)
    {
        $deleteForm = $this->createDeleteForm($purchase);

        return $this->render(
            'purchase/show.html.twig',
            [
                'purchase' => $purchase,
                'delete_form' => $deleteForm->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing purchase entity.
     *
     * @Route("/{id}/edit", name="purchase_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Purchase $purchase
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, Purchase $purchase)
    {
        $deleteForm = $this->createDeleteForm($purchase);
        $editForm = $this->createForm('AppBundle\Form\PurchaseType', $purchase);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('purchase_edit', ['id' => $purchase->getId()]);
        }

        return $this->render(
            'purchase/edit.html.twig',
            [
                'purchase' => $purchase,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ]
        );
    }

    /**
     * Deletes a purchase entity.
     *
     * @Route("/{id}", name="purchase_delete")
     * @Method("DELETE")
     *
     * @param Request $request
     * @param Purchase $purchase
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Purchase $purchase)
    {
        $form = $this->createDeleteForm($purchase);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($purchase);
            $em->flush();
        }

        return $this->redirectToRoute('purchase_index');
    }
}
