<?php

namespace PCBuild\MainBundle\Entity;

use Doctrine\ORM\PersistentCollection;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * Build
 *
 * @ORM\Table(name="build")
 * @ORM\Entity(repositoryClass="PCBuild\MainBundle\Repository\BuildRepository")
 */
class Build
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $title;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    private $totalprice;

    /**
     * @ORM\Column(type="text")
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
     * @ORM\ManyToMany(targetEntity="Component", inversedBy="builds")
     * @ORM\JoinTable(name="build_components")
     */
    private $components;

    public function __construct()
    {
        $this->components = new ArrayCollection();
    }

    public function addComponent(Component $component)
    {
        $component->addComponent($this);
        $this->components[] = $component;
    }

    public function removeComponent(Component $component)
    {
        $this->components->removeElement($component);

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getTotalprice()
    {
        return $this->totalprice;
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

    public function getComponents()
    {
        return $this->components;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setTotalprice($totalprice)
    {
        $this->totalprice = $totalprice;
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

    public function setComponents($components)
    {
        $this->components = $components;
    }
}
