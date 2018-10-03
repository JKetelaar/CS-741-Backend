<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Service\SerializerManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Category controller.
 *
 * @Route("category")
 */
class CategoryController extends Controller
{
    /**
     * Lists all category entities.
     *
     * @Route("/", name="category_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('AppBundle:Category')->findAll();

        return new JsonResponse(SerializerManager::normalize($categories));
    }

    /**
     * Finds and displays a category entity.
     *
     * @param Category $category
     * @return JsonResponse
     *
     * @Route("/{id}", name="category_show")
     * @Method("GET")
     */
    public function showAction(Category $category)
    {
        return new JsonResponse(SerializerManager::normalize($category));
    }
}
