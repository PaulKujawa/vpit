<?php

namespace Barra\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ingredient
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Barra\FrontBundle\Entity\IngredientRepository")
 */
class Ingredient
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=30, unique=true, nullable=false)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="vegan", type="boolean", nullable=false)
     */
    private $vegan;

    /**
     * @var string
     *
     * @ORM\Column(name="kcal", type="decimal", scale=2, nullable=false)
     */
    private $kcal;

    /**
     * @var string
     *
     * @ORM\Column(name="protein", type="decimal", scale=2, nullable=false)
     */
    private $protein;

    /**
     * @var string
     *
     * @ORM\Column(name="carbs", type="decimal", scale=2, nullable=false)
     */
    private $carbs;

    /**
     * @var string
     *
     * @ORM\Column(name="sugar", type="decimal", scale=2, nullable=false)
     */
    private $sugar;

    /**
     * @var string
     *
     * @ORM\Column(name="fat", type="decimal", scale=2, nullable=false)
     */
    private $fat;

    /**
     * @var string
     *
     * @ORM\Column(name="gfat", type="decimal", scale=2, nullable=false)
     */
    private $gfat;

    /**
     * @var string
     * @ORM\ManyToOne(targetEntity="Manufacturer")
     * @ORM\JoinColumn(name="manufacturer", referencedColumnName="id", nullable=false)
     */
    private $manufacturer;

    /**
     * @var string
     * @ORM\OneToMany(targetEntity="RecipeIngredient", mappedBy="Ingredient")
     */
    private $recipeIngredients;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->recipeIngredients = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Own shortcut-method for direct access to related recipes
     * @return array
     */
    public function getRecipes()
    {
        $recipes = array();
        foreach ($this->getRecipeIngredients() as $recipeIngredient) {
            $recipes[] = $recipeIngredient->getRecipe();
        }
        return $recipes;
    }





    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Ingredient
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set vegan
     *
     * @param boolean $vegan
     * @return Ingredient
     */
    public function setVegan($vegan)
    {
        $this->vegan = $vegan;

        return $this;
    }

    /**
     * Get vegan
     *
     * @return boolean 
     */
    public function getVegan()
    {
        return $this->vegan;
    }

    /**
     * Set kcal
     *
     * @param string $kcal
     * @return Ingredient
     */
    public function setKcal($kcal)
    {
        $this->kcal = $kcal;

        return $this;
    }

    /**
     * Get kcal
     *
     * @return string 
     */
    public function getKcal()
    {
        return $this->kcal;
    }

    /**
     * Set protein
     *
     * @param string $protein
     * @return Ingredient
     */
    public function setProtein($protein)
    {
        $this->protein = $protein;

        return $this;
    }

    /**
     * Get protein
     *
     * @return string 
     */
    public function getProtein()
    {
        return $this->protein;
    }

    /**
     * Set carbs
     *
     * @param string $carbs
     * @return Ingredient
     */
    public function setCarbs($carbs)
    {
        $this->carbs = $carbs;

        return $this;
    }

    /**
     * Get carbs
     *
     * @return string 
     */
    public function getCarbs()
    {
        return $this->carbs;
    }

    /**
     * Set sugar
     *
     * @param string $sugar
     * @return Ingredient
     */
    public function setSugar($sugar)
    {
        $this->sugar = $sugar;

        return $this;
    }

    /**
     * Get sugar
     *
     * @return string 
     */
    public function getSugar()
    {
        return $this->sugar;
    }

    /**
     * Set fat
     *
     * @param string $fat
     * @return Ingredient
     */
    public function setFat($fat)
    {
        $this->fat = $fat;

        return $this;
    }

    /**
     * Get fat
     *
     * @return string 
     */
    public function getFat()
    {
        return $this->fat;
    }

    /**
     * Set gfat
     *
     * @param string $gfat
     * @return Ingredient
     */
    public function setGfat($gfat)
    {
        $this->gfat = $gfat;

        return $this;
    }

    /**
     * Get gfat
     *
     * @return string 
     */
    public function getGfat()
    {
        return $this->gfat;
    }

    /**
     * Set manufacturer
     *
     * @param \Barra\FrontBundle\Entity\Manufacturer $manufacturer
     * @return Ingredient
     */
    public function setManufacturer(\Barra\FrontBundle\Entity\Manufacturer $manufacturer)
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    /**
     * Get manufacturer
     *
     * @return \Barra\FrontBundle\Entity\Manufacturer 
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    /**
     * Add recipes
     *
     * @param \Barra\FrontBundle\Entity\RecipeIngredient $recipes
     * @return Ingredient
     */
    public function addRecipeIngredient(\Barra\FrontBundle\Entity\RecipeIngredient $recipeIngredient)
    {
        $this->recipeIngredients[] = $recipeIngredient;

        return $this;
    }

    /**
     * Remove recipes
     *
     * @param \Barra\FrontBundle\Entity\RecipeIngredient $recipes
     */
    public function removeRecipeIngredient(\Barra\FrontBundle\Entity\RecipeIngredient $recipeIngredient)
    {
        $this->recipeIngredients->removeElement($recipeIngredient);
    }

    /**
     * Get recipes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecipeIngredients()
    {
        return $this->recipeIngredients;
    }
}