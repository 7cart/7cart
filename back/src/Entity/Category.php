<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="categories")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", name="parent_id")
     */
    protected $parentId;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     */
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    protected $parent;

     /**
     * @ORM\Column(type="json_array", options={"jsonb": true}, nullable=true)
     */
    protected $title;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function __toString()
    {
        return json_encode($this->getTitle(), JSON_UNESCAPED_UNICODE);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getParent()
    {
        return (isset($this->parent) && $this->parent->getId() > 0) ? $this->parent : null;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    public function getParentId(): int
    {
        return $this->parentId;
    }


    public function setParentId($parentId): int
    {
        $this->parentId = $parentId;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getChildren()
    {
        return $this->children;
    }
}

