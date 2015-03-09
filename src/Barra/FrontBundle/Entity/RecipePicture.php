<?php

namespace Barra\FrontBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
/**
 * RecipePicture
 * @ExclusionPolicy("none")
 * @ORM\Table()
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */
class RecipePicture
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @ORM\Column(name="filename", type="string", length=255, unique=true)
     */
    private $filename;

    /**
     * @ORM\ManyToOne(targetEntity="Recipe", inversedBy="recipePictures")
     * @ORM\JoinColumn(name="recipe", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    protected $recipe;

    /**
     * @ORM\Column(name="size", type="integer")
     */
    private $size;

    /**
     * @Assert\File(maxSize="2M", mimeTypes = {"image/*"})
     */
    private $file;

    /**
     * temp var for encoded filename
     */
    private $temp;







    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'uploads/documents';
    }

    public function getAbsolutePath()
    {
        return $this->filename === null
            ? null
            : $this->getUploadRootDir().'/'.$this->filename;
    }

    public function getWebPath()
    {
        return $this->filename === null
            ? null
            : $this->getUploadDir().'/'.$this->filename;
    }







    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if ($this->getFile() !== null) {
            $filename = sha1(uniqid(mt_rand(), true));
            $extension = $this->getFile()->guessExtension();
            $this->title = $this->filename;
            $this->filename = $filename.'.'.$extension;
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if ($this->getFile() === null)
            return;

        $this->getFile()->move($this->getUploadRootDir(), $this->filename);

        if (isset($this->temp)) {
            unlink($this->getUploadRootDir().'/'.$this->temp);
            $this->temp = null;
        }
        $this->file = null;
    }

    /**
     * Called before entity removal
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath())
            unlink($file);
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
     * Set title
     *
     * @param string $title
     * @return RecipePicture
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set filename
     *
     * @param string $filename
     * @return RecipePicture
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param RecipePicture $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        if (isset($this->filename)) {
            $this->temp = $this->filename;
            $this->filename = null;
        } else
            $this->filename = $this->file->getClientOriginalName();
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set recipe
     *
     * @param \Barra\FrontBundle\Entity\Recipe $recipe
     * @return RecipePicture
     */
    public function setRecipe(\Barra\FrontBundle\Entity\Recipe $recipe = null)
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
     * Set size
     *
     * @param string $size
     * @return RecipePicture
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }
}
