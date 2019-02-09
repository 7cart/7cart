<?php

namespace App\Service\Filter;

use App\Entity\Attribute;

class FilterItem
{

    private $attribute;
    private $values;

    public function __construct(Attribute $attribute, array $values)
    {
        $this->attribute = $attribute;
        $this->values = $values;
    }

    public function getAttribute()
    {
        return $this->attribute;
    }

    public function getValues()
    {
        return $this->values;
    }

    public function getSQLForAttribute($con)
    {
        $resultSQL = '';
        $attrId = $this->attribute->getName();

        if ($this->getAttribute()->isRelated()) {

            $orClause = [];
            foreach ($this->values as $value) {
                if ($this->getAttribute()->isMultiValues()){
                    $orClause[] = 'n.attributes @> \'{' . $con->quoteidentifier($attrId) . ':[' . (int)$value . ']}\'';
                } else {
                    $orClause[] = 'n.attributes @> \'{' . $con->quoteidentifier($attrId) . ':' . (int)$value . '}\'';
                }
            }

            if ($orClause) {
                $resultSQL = '(' . implode(" OR ", $orClause) . ')';
            }

        } else if ($this->getAttribute()->isNumeric()) {

            if (isset($this->values['min']) || isset($this->values['max'])) {

                $orClause = [];
                if (isset($this->values['min'])) {
                    $orClause[] = '(n.attributes->>' . $con->quote($attrId) . ')::NUMERIC >= ' . floatval($this->values['min']);
                }

                if (isset($this->values['max']) && !empty($this->values['max'])) {
                    $orClause[] = '(n.attributes->>' . $con->quote($attrId) . ')::NUMERIC <= ' . floatval($this->values['max']);
                }

                if ($orClause) {
                    $resultSQL = '(' . implode(" AND ", $orClause) . ')';
                }

            }

        }

        return ($resultSQL) ? $resultSQL : '1=1';
    }
}

