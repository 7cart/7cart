<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class NodeRepository extends EntityRepository
{
    public function findNodesByCategory(int $catId, $filter = '')
    {
        $builder = $this->createQueryBuilder('n')
        ->where('CONTAINS(n.categoriesId, :cid) = true')
        ->setParameter('cid', $catId);

        $sqlArr = [];
        foreach ($this->_parseFilter($filter) as $attrId => $values) {
            if (isset($values['min']) || isset($values['max'])) {
                if (isset($values['min'])) {
                    $sqlArr[] = 'GET_NUMERIC_JSON_FIELD(n.attributes, :attMin' . $attrId . ') >= ' . floatval($values['min']);
                    $builder->setParameter(':attMin' . $attrId, $attrId);
                }

                if (isset($values['max'])) {
                    $sqlArr[] = 'GET_NUMERIC_JSON_FIELD(n.attributes, :attMax' . $attrId . ') <= ' . floatval($values['max']);
                    $builder->setParameter(':attMax' . $attrId, $attrId);
                }
            } else {
                $orClouse = [];
                foreach ($values as $valId => $value) {
                    $key = $attrId . '_' . $valId;
                    $orClouse[] = 'CONTAINS(n.attributes, :f' . $key . ') = true';
                    $builder->setParameter(':f' . $key, '{"' . $attrId . '":[' . $value . ']}');
                }
                $sqlArr[] = '(' . implode(" OR ", $orClouse) . ')';
            }
        }

        if ($sqlArr) {
            $builder->andWhere(implode(" AND ", $sqlArr));
        }

        $builder->setMaxResults(20);
//var_dump($sqlArr); exit;
        return $builder->getQuery()->execute();
    }

    public function countNodesByCategory(int $catId, $filterStr = '')
    {
        $filter = $this->_parseFilter($filterStr);
        $availableAttributes = $this->_getAttrinutesFromCategory($catId);
        $con = $this->getEntityManager()->getConnection();
        $sqlArr = [];
        $sql = 'WITH intersection AS (
            SELECT n.*
            FROM nodes n
            WHERE  n.categories_id @> ' . $con->quote($catId) . '
        ) ';

        foreach ($availableAttributes as $attribute) {
            $localWhere = [];
            $localWhere[] = '(jsonb_exists(n.attributes, ' . $con->quote($attribute['key']) . '))';
            foreach ($filter as $attrId => $values) {
                if ($attribute['key'] == $attrId){
                    continue;
                }

                $orClouse = [];
                foreach ($values as $value) {
                    $orClouse[] = 'n.attributes @> \'{' . $con->quoteIdentifier($attrId) . ':[' . $value . ']}\'';
                }

                if ($orClouse) {
                    $localWhere[] = '(' . implode(" OR ", $orClouse) . ')';
                }
            }

            $sqlArr[] = 'SELECT ' . $con->quote($attribute['key']) . '  as _attr_id, 
                    n.attributes->>' . $con->quote($attribute['key']) . ' as _val_id, 
                    count(n.attributes->>' . $con->quote($attribute['key']) . ') as _count
                    FROM intersection n
                    WHERE ' . implode(" AND ", $localWhere). '
                    GROUP BY _val_id';
        }


        if (empty($sqlArr)){
            return [];
        }

        $sql.= 'SELECT subq._attr_id as attr_id, 
                case when subq._val_id LIKE \'[%\'
                  then (jsonb_array_elements_text(subq._val_id::jsonb))::int
                  else 0
                end as val_id, 
                SUM(subq._count) as count 
                FROM (' . implode(" UNION ", $sqlArr) . ') as subq 
                GROUP BY attr_id, val_id';

        $statement = $con->prepare($sql);
        $statement->execute();
        return $statement->fetchAll();
    }

    private function _getAttrinutesFromCategory($catId)
    {
        $sql =  '
            SELECT DISTINCT jsonb_object_keys(n.attributes) as key
            FROM nodes n
            WHERE n.categories_id @> :catId';

        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->bindValue('catId', $catId);
        $statement->execute();

        return $statement->fetchAll();
    }

    private function _parseFilter($filterStr)
    {
        $filterArr = [];
        $filterStr = str_replace('f_', '', strtolower($filterStr));
        if ($filterStr) {
            foreach (explode(';', $filterStr) as $section) {
                if ($section) {
                    $result = explode("=", $section);
                    $attr = (isset($result[0])) ? $result[0] : null;
                    $values = (isset($result[1])) ? $result[1] : null;
                    if ($attr && $values) {
                        $matches =null;
                        preg_match('/(-*\d+)-(-*\d+)/i', $values, $matches);
                        if (count($matches) == 3) {
                            $filterArr[$attr] = ["min" => $matches[1], "max" => $matches[2]];
                        } else {
                            $filterArr[$attr] = explode(',', $values);
                            $filterArr[$attr] = array_map(function ($element) {
                                return (int)$element;
                            },
                                $filterArr[$attr]
                            );
                        }
                    }
                }
            };
        }
//var_dump($filterArr); exit;
        return $filterArr;
    }

}