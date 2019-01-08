<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Service\Filter\FilterCollection;
use App\Entity\Node;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

class NodeRepository extends EntityRepository
{

    private function buildNodesQuery($catId, $filters)
    {
        $con = $this->getEntityManager()->getConnection();
        $sql = '';
        $sqlArr = [];

        if ($catId) {
            $sqlArr[] = 'n.categories_id @> ' . $con->quote($catId);
        }

        foreach ($filters as $filter) {
            $sqlArr[] = $filter->getSQLForAttribute($con);
        }

        if ($sqlArr) {
            $sql .= ' WHERE ' . implode(" AND ", $sqlArr);
        }
        return $sql;
    }

    public function findNodesByCategory(int $catId, FilterCollection $filters, int $page = 1, int $perPage = 10)
    {
        $page = abs($page);
        $perPage = abs($perPage);

        $sql = 'SELECT n.* FROM nodes as n';
        $sql .= $this->buildNodesQuery($catId, $filters);

        $sql .= ' LIMIT ' . (int)$perPage . ' OFFSET ' . ((int)$page - 1) * (int)$perPage;

        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(Node::class, 'n');
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        return $query->getResult();
    }

    public function countNodesByCategory($catId, $filters)
    {
        $con = $this->getEntityManager()->getConnection();
        $sql = 'SELECT count(*) FROM nodes as n';
        $sql .= $this->buildNodesQuery($catId, $filters);

        $statement = $con->prepare($sql);
        $statement->execute();
        $res = $statement->fetch();

        return (isset($res['count'])) ? $res['count'] : 0;
    }

    public function countAttributesByCategory(int $catId, FilterCollection $filters, $attributes)
    {
        $con = $this->getEntityManager()->getConnection();
        $sqlArr = [];
        $sql = 'WITH intersection AS (
            SELECT n.*
            FROM nodes n
            WHERE  n.categories_id @> ' . $con->quote($catId) . '
        ) ';

        foreach ($attributes as $attribute) {
            $key = $attribute->getName();
            $localWhere = [];
            $localWhere[] = '(jsonb_exists(n.attributes, ' . $con->quote($key) . '))';
            foreach ($filters as $filter) {
                if ($key == $filter->getAttribute()->getName()) {
                    continue;
                }

                $localWhere[] = $filter->getSQLForAttribute($con);
            }

            $sqlArr[] = 'SELECT ' . $con->quote($key) . '  as _attr_id, 
                    n.attributes->>' . $con->quote($key) . ' as _val_id, 
                    count(n.attributes->>' . $con->quote($key) . ') as _count
                    FROM intersection n
                    WHERE ' . implode(" AND ", $localWhere) . '
                    GROUP BY _val_id';
        }

        if (empty($sqlArr)) {
            return [];
        }

        $sql .= 'SELECT (subq._attr_id)::VARCHAR as attr_id, 
                case when subq._val_id LIKE \'[%\'
                  then (jsonb_array_elements_text(subq._val_id::jsonb))
                  else subq._val_id
                end as val_id, 
                SUM(subq._count) as count 
                FROM (' . implode(" UNION ", $sqlArr) . ') as subq 
                GROUP BY attr_id, val_id';

        $statement = $con->prepare($sql);
        $statement->execute();
        return $statement->fetchAll();
    }

}