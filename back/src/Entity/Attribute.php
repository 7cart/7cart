<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="attributes")
 */
class Attribute
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * Many Users have Many Groups.
     * @ORM\OneToMany(targetEntity="AttributeValue", mappedBy="attribute", fetch="EAGER")
     */
    private $values;


    public function __construct()
    {
        $this->values = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param mixed $values
     */
    public function setValues($values): void
    {
        $this->values = $values;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }
}

