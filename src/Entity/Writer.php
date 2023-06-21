<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\WriterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WriterRepository::class)]
#[ApiResource]
class Writer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getMovies", "getWriters"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getMovies", "getWriters"])]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getMovies", "getWriters"])]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getMovies", "getWriters"])]
    private ?string $birthDate = null;

    #[Groups(["getWriters"])]
    #[ORM\ManyToMany(targetEntity: Movie::class, mappedBy: 'writers')]
    private Collection $movies;

    public function __construct()
    {
        $this->movies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getBirthDate(): ?string
    {
        return $this->birthDate;
    }

    public function setBirthDate(string $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * @return Collection<int, Movie>
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    public function addMovie(Movie $movie): static
    {
        if (!$this->movies->contains($movie)) {
            $this->movies->add($movie);
            $movie->addWriter($this);
        }

        return $this;
    }

    public function removeMovie(Movie $movie): static
    {
        if ($this->movies->removeElement($movie)) {
            $movie->removeWriter($this);
        }

        return $this;
    }
}
