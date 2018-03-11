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
     * @ORM\Column(type="json_array", options={"jsonb": true})
     */
    protected $title;

    /**
     * @ORM\Column(type="json_array", options={"jsonb": true})
     */
    protected $categoriesId;

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

}

