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
     * @param int|null $category
     *
     * @return Product[]
     */
    public function getProducts(
        ?string $orderBy = null,
        ?string $orderType = null,
        ?string $limit = null,
        ?string $search = null,
        ?int $category = null
    ): array {
        $query = $this->createQueryBuilder('product')
            ->from('AppBundle:Product', 'p');

        $query->andWhere('product.active = :active');
        $query->setParameter('active', true);

        if ($category !== null) {
            $query->andWhere('product.category = :category');
            $query->setParameter('category', $category);
        }

        if ($search !== null) {
            $query->andWhere('(product.name LIKE :search OR product.description LIKE :search)')
                ->setParameter('search', '%'.$search.'%');
        }

        if ($orderBy !== null) {
            if ($orderType === null) {
                $orderType = 'DESC';
            }

//            $query->addOrderBy('product.'.$orderBy, $orderType);
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