<?php

namespace PCBuild\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Component
 *
 * @ORM\Table(name="component")
 * @ORM\Entity(repositoryClass="PCBuild\MainBundle\Repository\ComponentRepository")
 */
class Component
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    private $price;

    /**
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $created_by;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $updated_by;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\ManyToMany(targetEntity="Build", mappedBy="components")
     */
    private $builds;

    public function addComponent(Build $build)
    {
        $this->builds[] = $build;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getCreated_by()
    {
        return $this->created_by;
    }

    public function getUpdated_by()
    {
        return $this->updated_by;
    }

    public function getCreated_at()
    {
        return $this->created_at;
    }

    public function getUpdated_at()
    {
        return $this->updated_at;
    }

    public function getBuilds()
    {
        return $this->builds;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }
    
    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setCreated_by($created_by)
    {
        $this->created_by = $created_by;
    }

    public function setUpdated_by($updated_by)
    {
        $this->updated_by = $updated_by;
    }

    public function setCreated_at($created_at)
    {
        $this->created_at = $created_at;
    }

    public function setUpdated_at($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    public function setBuilds($builds)
    {
        $this->builds = $builds;
    }
}
