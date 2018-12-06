<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="nodes")
 * @ORM\Entity(repositoryClass="App\Repository\NodeRepository")
 */
class Node
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

     /**
     * @ORM\Column(type="json_array", options={"jsonb": true}, nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(type="json_array", options={"jsonb": true}, nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="json_array", options={"jsonb": true})
     */
    protected $categoriesId;

    /**
     * @ORM\Column(type="json_array", options={"jsonb": true}, nullable=true)
     */
    protected $attributes;

    /**
     * @return mixed
     */

    public function getPrice(){return $this->attributes['price'];}
public function getDescription(){return $this->description;}


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


}

