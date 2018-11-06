<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Promotion;
use AppBundle\Form\PromotionType;
use AppBundle\Service\SerializerManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Promotion controller.
 *
 * @Route("admin/promotion")
 */
class AdminPromotionController extends Controller
{
    /**
     * Lists all promotion entities.
     *
     * @Route("/", name="admin_promotion_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $promotions = $em->getRepository('AppBundle:Promotion')->findAll();

        return new JsonResponse(SerializerManager::normalize($promotions));
    }

    /**
     * Creates a new promotion entity.
     *
     * @Route("/new", name="admin_promotion_new")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function newAction(Request $request)
    {
        $promotion = new Promotion();
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($promotion);
            $em->flush();

            return new JsonResponse(SerializerManager::normalize($promotion));
        } else {
            $errors = array();
            foreach ($form->all() as $child) {
                $fieldName = $child->getName();
                $fieldErrors = $form->get($child->getName())->getErrors(true);

                foreach ($fieldErrors as $fieldError){
                    $errors[$fieldName] = $fieldError->getMessage();
                }
            }
            return new JsonResponse(['error' => 'Could not create promotion', 'errors' => $errors], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Finds and displays a promotion entity.
     *
     * @Route("/{id}", name="admin_promotion_show")
     * @Method("GET")
     *
     * @param Promotion $promotion
     * @return JsonResponse
     */
    public function showAction(Promotion $promotion)
    {
        return new JsonResponse(SerializerManager::normalize($promotion));
    }

    /**
     * Displays a form to edit an existing promotion entity.
     *
     * @Route("/{id}/edit", name="admin_promotion_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Promotion $promotion
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, Promotion $promotion)
    {
        $deleteForm = $this->createDeleteForm($promotion);
        $editForm = $this->createForm('AppBundle\Form\PromotionType', $promotion);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_promotion_edit', ['id' => $promotion->getId()]);
        }

        return $this->render(
            'promotion/edit.html.twig',
            [
                'promotion' => $promotion,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ]
        );
    }

    /**
     * Deletes a promotion entity.
     *
     * @Route("/{id}", name="admin_promotion_delete")
     * @Method("DELETE")
     *
     * @param Request $request
     * @param Promotion $promotion
     * @return JsonResponse
     */
    public function deleteAction(Request $request, Promotion $promotion)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($promotion);
        $em->flush();

        return new JsonResponse(['Promotion deleted']);
    }
}
