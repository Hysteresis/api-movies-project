<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
#[ApiResource()]
class Genre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getMovies", "getGenres"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getMovies", "getGenres"])]
    #[Assert\NotBlank(message: "Le titre du genre est obligatoire")]
    #[Assert\Length(min: 1, max: 255, minMessage: "Le titre doit faire au moins {{ limit }} caractères", 
                    maxMessage: "Le titre ne peut pas faire plus de {{ limit }} caractères")]
    private ?string $title = null;

    #[ORM\ManyToMany(targetEntity: Movie::class, mappedBy: 'genres')]
    #[Groups(["getGenres"])]
    private Collection $movies;

    public function __construct()
    {
        $this->movies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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
            $movie->addGenre($this);
        }

        return $this;
    }

    public function removeMovie(Movie $movie): static
    {
        if ($this->movies->removeElement($movie)) {
            $movie->removeGenre($this);
        }

        return $this;
    }
}
