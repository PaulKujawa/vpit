<?php

namespace Barra\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RecipeIngredient
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class RecipeIngredient
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Recipe", inversedBy="RecipeIngredient")
     * @ORM\JoinColumn(name="recipe", referencedColumnName="id", nullable=false)
     */
    private $recipe;

    /**
     * @var integer
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Ingredient", inversedBy="RecipeIngredient")
     * @ORM\JoinColumn(name="ingredient", referencedColumnName="id", nullable=false)
     */
    private $ingredient;

    /**
     * @var integer
     * @ORM\Column(name="position", type="smallint", nullable=false)
     */
    private $position;

    /**
     * @var integer
     * @ORM\Column(name="amount", type="smallint", nullable=false)
     */
    private $amount;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="Measurement")
     * @ORM\JoinColumn(name="measurement", referencedColumnName="id", nullable=false)
     */
    private $measurement;

    /**
     * Set position
     *
     * @param integer $position
     * @return RecipeIngredient
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     * @return RecipeIngredient
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return RecipeIngredient
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set recipe
     *
     * @param \Barra\FrontBundle\Entity\Recipe $recipe
     * @return RecipeIngredient
     */
    public function setRecipe(\Barra\FrontBundle\Entity\Recipe $recipe)
    {
        $this->recipe = $recipe;

        return $this;
    }

    /**
     * Get recipe
     *
     * @return \Barra\FrontBundle\Entity\Recipe 
     */
    public function getRecipe()
    {
        return $this->recipe;
    }

    /**
     * Set ingredient
     *
     * @param \Barra\FrontBundle\Entity\Ingredient $ingredient
     * @return RecipeIngredient
     */
    public function setIngredient(\Barra\FrontBundle\Entity\Ingredient $ingredient)
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    /**
     * Get ingredient
     *
     * @return \Barra\FrontBundle\Entity\Ingredient 
     */
    public function getIngredient()
    {
        return $this->ingredient;
    }

    /**
     * Set measurement
     *
     * @param \Barra\FrontBundle\Entity\Measurement $measurement
     * @return RecipeIngredient
     */
    public function setMeasurement(\Barra\FrontBundle\Entity\Measurement $measurement)
    {
        $this->measurement = $measurement;

        return $this;
    }

    /**
     * Get measurement
     *
     * @return \Barra\FrontBundle\Entity\Measurement 
     */
    public function getMeasurement()
    {
        return $this->measurement;
    }
}