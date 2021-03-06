<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\IdAutoTrait;
use AppBundle\Entity\Traits\NameTrait;
use AppBundle\Entity\Traits\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntity("name")
 *
 * @ORM\Table()
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class Recipe
{
    use IdAutoTrait;
    use NameTrait;
    use TimestampTrait;

    /**
     * @var bool
     *
     * @Assert\NotNull()
     *
     * @ORM\Column(
     *     name = "isVegan",
     *     type = "boolean"
     * )
     */
    public $isVegan;

    /**
     * @var int
     *
     * @Assert\NotNull()
     *
     * @ORM\Column(type = "integer")
     */
    public $servings;

    /**
     * @var int|null
     *
     * @ORM\Column(
     *     name = "preparationTime",
     *     type = "integer",
     *     nullable = true
     * )
     */
    public $preparationTime;

    /**
     * @var int|null
     *
     * @ORM\Column(
     *     name = "cookTime",
     *     type = "integer",
     *     nullable = true
     * )
     */
    public $cookTime;

    /**
     * @var string
     *
     * @ORM\Column(type = "text")
     */
    public $description;

    /**
     * @var int
     *
     * @Serializer\Exclude()
     *
     * @Assert\NotNull()
     *
     * @ORM\Column(
     *     type = "integer",
     *     name = "photosAmount"
     * )
     */
    public $photosAmount;

    /**
     * @var ArrayCollection
     *
     * @Serializer\Groups({"recipeDetail"})
     *
     * @ORM\OneToMany(
     *      targetEntity = "Ingredient",
     *      mappedBy = "recipe"
     * )
     */
    public $ingredients;

    /**
     * @var ArrayCollection
     *
     * @Serializer\Groups({"recipeDetail"})
     *
     * @ORM\OneToMany(
     *      targetEntity = "Instruction",
     *      mappedBy = "recipe"
     * )
     * @ORM\OrderBy({"position" = "ASC"})
     */
    public $instructions;

    public function __construct()
    {
        $this->instructions = new ArrayCollection();
        $this->ingredients = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function generateIsVegan()
    {
        $notVeganProducts = $this->ingredients->filter(function(Ingredient $ingredient) {
            return !$ingredient->product->vegan;
        });

        $this->isVegan = $notVeganProducts->count() === 0;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("photos")
     */
    public function getPhotos(): array
    {
        $photos = [];

        for ($i = 1; $i <= $this->photosAmount; $i++) {
            $photos[] = sprintf('images/recipes/%d/%d.jpg', $this->id, $i);
        }

        return $photos;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("macros")
     */
    public function getMacros(): array
    {
        $macros = [
            'kcal' => 0,
            'carbs' => 0,
            'protein' => 0,
            'fat' => 0,
        ];

        $ingredients = $this->ingredients->filter(function(Ingredient $ingredient) {
            return null !== $ingredient->amount;
        });

        /**
         * @var Ingredient $ingredient
         */
        foreach ($ingredients as $ingredient) {
            $rel = $this->getRelation($ingredient);
            $product = $ingredient->product;
            $macros['kcal'] += $rel * $product->kcal;
            $macros['carbs'] += $rel * $product->carbs;
            $macros['protein'] += $rel * $product->protein;
            $macros['fat'] += $rel * $product->fat;
        }

        return array_map('intval', $macros);
    }

    private function getRelation(Ingredient $ingredient): float {
        $gr = $ingredient->measurement->gr ?: $ingredient->product->gr;

        return $ingredient->amount * $gr / 100;
    }
}
