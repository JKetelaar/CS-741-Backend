<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Promotion;
use AppBundle\Form\PromotionType;
use AppBundle\Service\SerializerManager;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Promotion controller.
 *
 * @Route("admin/promotion")
 * @IsGranted("ROLE_ADMIN", statusCode=401, message="Method not allowed with current credentials")
 */
class AdminPromotionController extends Controller
{
    /**
     * Lists all promotion entities.
     *
     * @Route("/", name="admin_promotion_index", methods={"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns all available promotions",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=AppBundle\Entity\Promotion::class))
     *     )
     * )
     * @SWG\Tag(name="promotion")
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
     * @Route("/new", name="admin_promotion_new", methods={"POST"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Creates a new promotion",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=AppBundle\Entity\Promotion::class))
     *     )
     * )
     *
     * @SWG\Parameter(
     *     name="code",
     *     in="formData",
     *     type="string",
     *     description="Code for the promotion"
     * )
     *
     * @SWG\Parameter(
     *     name="percentage",
     *     in="formData",
     *     type="integer",
     *     description="Percentage of promotion"
     * )
     *
     * @SWG\Parameter(
     *     name="expirationDate",
     *     in="formData",
     *     type="string",
     *     description="Date of expiration (such as 2018-11-11 10:10:10)"
     * )
     *
     * @SWG\Post(consumes={"application/x-www-form-urlencoded"})
     *
     * @SWG\Tag(name="promotion")
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
            return new JsonResponse(
                ['error' => 'Could not create promotion', 'errors' => $this->get('form_error_helper')->getFormErrors($form)],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Finds and displays a promotion entity.
     *
     * @Route("/{id}", name="admin_promotion_show", methods={"GET"})
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
     * @Route("/{id}/edit", name="admin_promotion_edit", methods={"POST"})
     *
     * @param Request $request
     * @param Promotion $promotion
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, Promotion $promotion)
    {
        $editForm = $this->createForm(PromotionType::class, $promotion);
        $editForm->handleRequest($request);

        $editForm->submit($request->request->all());

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse(SerializerManager::normalize($promotion));
        } else {
            return new JsonResponse(
                ['error' => 'Could not edit promotion', 'errors' => $this->get('form_error_helper')->getFormErrors($editForm)],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Deletes a promotion entity.
     *
     * @Route("/{id}", name="admin_promotion_delete", methods={"DELETE"})
     *
     * @param Request $request
     * @param Promotion $promotion
     * @return JsonResponse
     *
     * @SWG\Response(
     *     response=200,
     *     description="Deletes a promotion",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=AppBundle\Entity\Promotion::class))
     *     )
     * )
     *
     * @SWG\Tag(name="promotion")
     */
    public function deleteAction(Request $request, Promotion $promotion)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($promotion);
        $em->flush();

        return new JsonResponse(['Promotion deleted']);
    }
}
