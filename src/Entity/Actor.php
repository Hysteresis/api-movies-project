<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ActorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ActorRepository::class)]
#[ApiResource()]
class Actor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getMovies", "getActors"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getMovies", "getActors"])]
    #[Assert\NotBlank(message: "Le nom de l'acteur est obligatoire")]
    #[Assert\Length(min: 1, max: 255, minMessage: "Le nom doit faire au moins {{ limit }} caractères", 
                    maxMessage: "Le nom ne peut pas faire plus de {{ limit }} caractères")]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getMovies", "getActors"])]
    #[Assert\NotBlank(message: "Le prénom de l'acteur est obligatoire")]
    #[Assert\Length(min: 1, max: 255, minMessage: "Le prénom doit faire au moins {{ limit }} caractères", 
                    maxMessage: "Le prénom ne peut pas faire plus de {{ limit }} caractères")]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getMovies", "getActors"])]
    private ?string $birthDate = null;

    #[Groups(["getActors"])]
    #[ORM\ManyToMany(targetEntity: Movie::class, mappedBy: 'actors')]
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
            $movie->addActor($this);
        }

        return $this;
    }

    public function removeMovie(Movie $movie): static
    {
        if ($this->movies->removeElement($movie)) {
            $movie->removeActor($this);
        }

        return $this;
    }
}
