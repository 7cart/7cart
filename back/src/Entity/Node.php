<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="nodes")
 * @ORM\Entity(repositoryClass="App\Repository\NodeRepository")
 */
class Node
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", options={"default":"NEXTVAL('categories_id_seq')"})
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     */
    protected $id;

     /**
     * @ORM\Column(type="json_array", options={"jsonb": true}, unique=true)
     */
    protected $title;

    /**
     * @ORM\Column(type="json_array", options={"jsonb": true}, nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="json_array", options={"jsonb": true}, nullable=true)
     */
    protected $categoriesId;

    //use only in admin form  builder
    protected $categories;

    /**
     * @ORM\Column(type="json_array", options={"jsonb": true}, nullable=true)
     */
    protected $attributes;


    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function setCategories($categories)
    {
        $arr = [];
        $this->categories = $categories;
        foreach ($this->categories as $category) {
            $arr[] = $category->getId();
        }
        $this->categoriesId = $arr;
    }

    public function __toString()
    {
        return json_encode($this->getTitle(), JSON_UNESCAPED_UNICODE);
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return isset($this->attributes['price']) ? $this->attributes['price'] : 0;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @return mixed
     */
    public function getCategoriesId()
    {
        return $this->categoriesId;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }


    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @param mixed $categoriesId
     */
    public function setCategoriesId($categoriesId): void
    {
        $this->categoriesId = $categoriesId;
    }

    /**
     * @param mixed $attributes
     */
    public function setAttributes($attributes): void
    {
        $this->attributes = $attributes;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

}

