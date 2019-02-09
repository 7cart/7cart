<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="attributes")
 * @UniqueEntity(fields="name", message="Name is already taken.")
 */
class Attribute
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\Regex("/^[a-z_]+[0-9]*$/")
     */
    protected $name;

    /**
     *
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

    public function __toString()
    {
        return $this->getName();
    }

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

    public function setName($name)
    {
        $this->name = $name;
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


    public function isMultiValues()
    {
        return in_array($this->inputType, ['multiSelect', 'multiText']);
    }

    public function isRelated()
    {
        return in_array($this->inputType, ['multiSelect', 'select']);
    }

    public function isNumeric()
    {
        return ((!$this->isMultiValues()) &&
            ($this->dataType == 'numeric' || $this->dataType == 'integer')
        );
    }
}

