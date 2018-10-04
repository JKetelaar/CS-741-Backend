<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Product;

/**
 * ProductRepository
 */
class ProductRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param string|null $orderBy
     * @param string|null $orderType
     * @param string|null $limit
     * @param string|null $search
     *
     * @return Product[]
     */
    public function getProducts(
        ?string $orderBy = null,
        ?string $orderType = null,
        ?string $limit = null,
        ?string $search = null
    ): array {
        $query = $this->createQueryBuilder('product')
            ->from('AppBundle:Product', 'p');

        if ($search !== null) {
            $query->where('product.name LIKE :search')
                ->orWhere('product.description LIKE :search')
                ->setParameter('search', '%'.$search.'%');
        }

        if ($orderBy !== null) {
            if ($orderType === null) {
                $orderType = 'DESC';
            }

            $query->orderBy('product.'.$orderBy, $orderType);
        }

        if ($limit !== null) {
            $query->setMaxResults($limit);
        }

        $products = $query->getQuery()->getResult();
        if ($products !== null && is_array($products)) {
            return $products;
        }

        return [];
    }
}