<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource() en annotation sur votre classe
 * @ORM\Entity(repositoryClass=ProductsRepository::class)
 */
class Products
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     * message = "Ce champ n'est pas du tout valide."
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $extaxprice;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="string", length=800, nullable=true)
     */
    private $imgpath;

    /**
     * @ORM\ManyToOne(targetEntity=Brands::class, inversedBy="products")
     */
    private $fk_brand;

    /**
     * @ORM\ManyToMany(targetEntity=Categories::class, inversedBy="products")
     */
    private $fk_categories;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $images;

    public function __construct()
    {
        $this->fk_categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getExtaxprice(): ?float
    {
        return $this->extaxprice;
    }

    public function setExtaxprice(float $extaxprice): self
    {
        $this->extaxprice = $extaxprice;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getImgpath(): ?string
    {
        return $this->imgpath;
    }

    public function setImgpath(?string $imgpath): self
    {
        $this->imgpath = $imgpath;

        return $this;
    }

    public function getFkBrand(): ?Brands
    {
        return $this->fk_brand;
    }

    public function setFkBrand(?Brands $fk_brand): self
    {
        $this->fk_brand = $fk_brand;

        return $this;
    }

    /**
     * @return Collection|Categories[]
     */
    public function getFkCategories(): Collection
    {
        return $this->fk_categories;
    }

    public function addFkCategory(Categories $fkCategory): self
    {
        if (!$this->fk_categories->contains($fkCategory)) {
            $this->fk_categories[] = $fkCategory;
        }

        return $this;
    }

    public function removeFkCategory(Categories $fkCategory): self
    {
        $this->fk_categories->removeElement($fkCategory);

        return $this;
    }

    public function getImages(): ?string
    {
        return $this->images;
    }

    public function setImages(?string $images): self
    {
        $this->images = $images;

        return $this;
    }
}
