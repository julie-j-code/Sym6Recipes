<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Recipes::class, orphanRemoval: true)]
    private $recipes;

    #[ORM\ManyToMany(targetEntity: Recipes::class, mappedBy: 'favorite')]
    private $favorites;

    #[ORM\Column(type: 'string', length: 100)]
    private $fullName;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private $pseudo;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Marks::class, orphanRemoval: true)]
    private $marks;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->recipe = new ArrayCollection();
    }

    // #[ORM\ManyToMany(targetEntity: Ingredients::class, inversedBy: 'users')]
    // private $ingredients;

    // public function __construct()
    // {
    //     $this->ingredients = new ArrayCollection();
    // }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }



    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    // /**
    //  * @return Collection<int, Ingredients>
    //  */
    // public function getIngredients(): Collection
    // {
    //     return $this->ingredients;
    // }

    // public function addIngredient(Ingredients $ingredient): self
    // {
    //     if (!$this->ingredients->contains($ingredient)) {
    //         $this->ingredients[] = $ingredient;
    //     }

    //     return $this;
    // }

    // public function removeIngredient(Ingredients $ingredient): self
    // {
    //     $this->ingredients->removeElement($ingredient);

    //     return $this;
    // }

    // public function __toString()
    // {
    //     return $this->ingredients;
    // }

    /**
     * @return Collection<int, Recipes>
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipes $recipe): self
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes[] = $recipe;
            $recipe->setUser($this);
        }

        return $this;
    }

    public function removeRecipe(Recipes $recipe): self
    {
        if ($this->recipes->removeElement($recipe)) {
            // set the owning side to null (unless already changed)
            if ($recipe->getUser() === $this) {
                $recipe->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Recipes>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Recipes $favorite): self
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites[] = $favorite;
            $favorite->addFavorite($this);
        }

        return $this;
    }

    public function removeFavorite(Recipes $favorite): self
    {
        if ($this->favorites->removeElement($favorite)) {
            $favorite->removeFavorite($this);
        }

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return Collection<int, Marks>
     */
    public function getMarks(): Collection
    {
        return $this->marks;
    }

}
