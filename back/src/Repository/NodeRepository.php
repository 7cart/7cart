<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class NodeRepository extends EntityRepository
{
    public function findNodesByCategory(int $catId)
    {
         $query = $this->createQueryBuilder('n')
            ->where('CONTAINS(n.categoriesId, :id) = true')
            ->setParameter('id', $catId)->getQuery();

        return $query;
    }
}