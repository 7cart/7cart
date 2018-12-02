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

    /**
     * @ORM\Column(type="string", length=20, options={"default": "string"})
     */
    protected $dataType;

    /**
     * @ORM\Column(type="string", length=20, options={"default": "text"})
     */
    protected $inputType;

    /**
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    protected $isActive;//filtered

    /**
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    protected $isMulti;


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

    /**
     * @return mixed
     */
    public function getDataType()
    {
        return $this->dataType;
    }

    /**
     * @param mixed $dataType
     */
    public function setDataType($dataType): void
    {
        $this->dataType = $dataType;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getIsMulti()
    {
        return $this->isMulti;
    }

    /**
     * @param mixed $isMulti
     */
    public function setIsMulti($isMulti): void
    {
        $this->isMulti = $isMulti;
    }

    /**
     * @return mixed
     */
    public function getInputType()
    {
        return $this->inputType;
    }

    /**
     * @param mixed $inputType
     */
    public function setInputType($inputType): void
    {
        $this->inputType = $inputType;
    }

}

