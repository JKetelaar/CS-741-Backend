<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Service\SerializerManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $product = new Product();
        $product
            ->setName('Test')
            ->setDescription('Test')
            ->setPrice(1);

        return new JsonResponse(SerializerManager::normalize($product));
    }
}
