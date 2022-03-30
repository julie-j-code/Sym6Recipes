<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MarkRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: MarkRepository::class)]
#[UniqueEntity(
    fields:['user','recipe'],
    errorPath: 'user',
    message:'Cet utilisateur a déjà noté cette recette'
)]

class Marks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    #[Assert\Positive()]
    #[Assert\LessThan(6)]
    private int $mark;

    #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: 'marks')]
    #[ORM\JoinColumn(nullable: false)]
    private Users $user;

    #[ORM\ManyToOne(targetEntity: Recipes::class, inversedBy: 'marks')]
    #[ORM\JoinColumn(nullable: false)]
    private Recipes $recipe;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMark(): ?int
    {
        return $this->mark;
    }

    public function setMark(int $mark): self
    {
        $this->mark = $mark;

        return $this;
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

    public function getRecipe(): ?Recipes
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipes $recipe): self
    {
        $this->recipe = $recipe;

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
}
