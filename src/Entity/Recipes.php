<?php

namespace App\Entity;

use App\Repository\RecipesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipesRepository::class)]
class Recipes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $Name;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $time;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $nbPeople;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $difficulty;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\Column(type: 'float', nullable: true)]
    private $price;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private $updatedAt;

    #[ORM\ManyToMany(targetEntity: Ingredients::class, inversedBy: 'recipes')]
    private $ingredients;

    // #[ORM\ManyToMany(targetEntity: Ingredients::class)]
    // private $ingredients;


    // #[ORM\Column(type: 'boolean', nullable: true)]
    // private $isFavorite;

    #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: 'boolean')]
    private $isPublic;

    #[ORM\ManyToMany(targetEntity: Users::class, inversedBy: 'favorites')]
    private $favorite;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Marks::class, orphanRemoval: true)]
    private $marks;

    private float $average;


    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable;
        $this->updatedAt = new \DateTimeImmutable();
        $this->favorite = new ArrayCollection();
        $this->marks = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(?int $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getNbPeople(): ?int
    {
        return $this->nbPeople;
    }

    public function setNbPeople(?int $nbPeople): self
    {
        $this->nbPeople = $nbPeople;

        return $this;
    }

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(?int $difficulty): self
    {
        $this->difficulty = $difficulty;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Ingredients>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredients $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
        }

        return $this;
    }

    public function removeIngredient(Ingredients $ingredient): self
    {
        $this->ingredients->removeElement($ingredient);

        return $this;
    }

    // public function getIsFavorite(): ?bool
    // {
    //     return $this->isFavorite;
    // }

    // public function setIsFavorite(?bool $isFavorite): self
    // {
    //     $this->isFavorite = $isFavorite;

    //     return $this;
    // }

        public function __toString()
    {
        return $this->ingredients;
    }

        public function getUser(): ?Users
        {
            return $this->user;
        }

        public function setUser(?Users $user): self
        {
            $this->user = $user;

            return $this;
        }

        public function getIsPublic(): ?bool
        {
            return $this->isPublic;
        }

        public function setIsPublic(bool $isPublic): self
        {
            $this->isPublic = $isPublic;

            return $this;
        }

        /**
         * @return Collection<int, Users>
         */
        public function getFavorite(): Collection
        {
            return $this->favorite;
        }

        public function addFavorite(Users $favorite): self
        {
            if (!$this->favorite->contains($favorite)) {
                $this->favorite[] = $favorite;
            }

            return $this;
        }

        public function removeFavorite(Users $favorite): self
        {
            $this->favorite->removeElement($favorite);

            return $this;
        }

        /**
         * @return Collection<int, Marks>
         */
        public function getMarks(): Collection
        {
            return $this->marks;
        }

        public function addMark(Marks $marks): self
        {
            if (!$this->marks->contains($marks)) {
                $this->marks[] = $marks;
                $marks->setRecipe($this);
            }

            return $this;
        }

        public function removeMark(Marks $marks): self
        {
            if ($this->marks->removeElement($marks)) {
                // set the owning side to null (unless already changed)
                if ($marks->getRecipe() === $this) {
                    $marks->setRecipe(null);
                }
            }

            return $this;
        }

        // Average Marks
        public function getAverage(): float
        {
            $marks = $this->marks;
            if ($marks->toArray() === []) {
                # code...
                $this->average = null;
                return $this->average;
            }

            $total=0;
            foreach ($marks as $mark) {
                # code...
                $total += $mark->getMark();
            }

            $this->average = $total /count($marks);
            return $this->average;
        }



}
