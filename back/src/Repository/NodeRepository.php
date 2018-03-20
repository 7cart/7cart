<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class NodeRepository extends EntityRepository
{
    public function findNodesByCategory(int $catId, $filter = '')
    {
        $builder = $this->createQueryBuilder('n')
        ->where('CONTAINS(n.categoriesId, :id) = true')
        ->setParameter('id', $catId);

        $filter = str_replace('f_', '', strtolower($filter));
        if ($filter) {
            $filterArr = [];
            foreach (explode(';', $filter) as $section) {
                if ($section) {
                    $result = explode("=", $section);
                    $attr = (isset($result[0])) ? $result[0] : null;
                    $values = (isset($result[1])) ? $result[1] : null;
                    if ($attr && $values) {
                        $filterArr[$attr] = explode(',', $values);
                        $filterArr[$attr] = array_map(function ($element) {
                            return (int)$element;
                        },
                            $filterArr[$attr]
                        );
                    }
                }
            };

            $sqlArr = [];
            foreach ($filterArr as $attrId => $values) {
                $sqlArr[] = 'CONTAINS(n.attributes, :filter' . $attrId . ') = true ';
                //format {"attrId": [values array]}
                $builder->setParameter('filter' . $attrId, '{"' . $attrId . '":' . json_encode($values) . '}');
            }

            $builder->andWhere(implode(" OR ", $sqlArr));
        }

        return $builder->getQuery();
    }
}