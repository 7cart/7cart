<?php

namespace App\Service;

use App\Service\Filter\FilterItem;
use App\Service\Filter\FilterCollection;
use Doctrine\ORM\EntityManagerInterface;

class Filter
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function selectFiltersByString(string $filterStr)
    {
        $result = new FilterCollection();
        $data = $this->parseFilter($filterStr);

        foreach ($this->selectActiveAttributesByName(array_keys($data)) as $attribute) {
            $values = $data[$attribute->getName()];
            $result[$attribute->getName()] = new FilterItem($attribute, $values);
        }

        return $result;
    }

    public function getAllActiveAttributesFromCategory($catId)
    {
        $sql = '
            SELECT DISTINCT jsonb_object_keys(n.attributes) as key
            FROM nodes n
            WHERE n.categories_id @> :catId';

        $statement = $this->em->getConnection()->prepare($sql);
        $statement->bindValue('catId', $catId);
        $statement->execute();

        $names = [];
        foreach ($statement->fetchAll() as $keys) {
            $names[] = $keys['key'];
        }

        return $this->selectActiveAttributesByName($names);
    }

    public function selectActiveAttributesByName(array $names)
    {
        return $this->em
            ->getRepository(\App\Entity\Attribute::class)
            ->findBy(['name' => $names, 'isActive' => true]);
    }

    public function parseFilter(string $filterStr)
    {
        $filterArr = [];
        $filterStr = str_replace('f_', '', strtolower($filterStr));
        if ($filterStr) {
            foreach (explode(';', $filterStr) as $section) {
                if ($section) {
                    $res = explode("=", $section);
                    $attr = (isset($res[0])) ? $res[0] : null;
                    $values = (isset($res[1])) ? $res[1] : null;
                    if ($attr && $values) {
                        $matches = null;
                        preg_match('/(-*\d+)-(-*\d+)*/i', $values, $matches);
                        // var_dump($matches); exit;
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

        return $filterArr;
    }

}