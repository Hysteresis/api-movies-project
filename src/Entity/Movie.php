<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
#[ApiResource]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getMovies", "getActors", "getWriters", "getGenres"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getMovies", "getActors", "getWriters", "getGenres"])]
    #[Assert\NotBlank(message: "Le titre du film est obligatoire")]
    #[Assert\Length(min: 1, max: 255, minMessage: "Le titre doit faire au moins {{ limit }} caractères", 
                    maxMessage: "Le titre ne peut pas faire plus de {{ limit }} caractères")]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getMovies", "getActors", "getWriters", "getGenres"])]
    #[Assert\NotBlank(message: "Le rated du film est obligatoire")]
    #[Assert\Length(min: 1, max: 10, minMessage: "Le rated doit faire au moins {{ limit }} caractères", 
                    maxMessage: "Le rated ne peut pas faire plus de {{ limit }} caractères")]
    private ?string $rated = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(["getMovies", "getActors", "getWriters", "getGenres"])]
    private ?\DateTimeInterface $released = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getMovies", "getActors", "getWriters", "getGenres"])]
    private ?string $runtime = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["getMovies", "getActors", "getWriters", "getGenres"])]
    private ?string $plot = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getMovies", "getActors", "getWriters", "getGenres"])]
    private ?string $poster = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getMovies", "getActors", "getWriters", "getGenres"])]
    private ?string $slug = null;

    #[ORM\ManyToMany(targetEntity: Actor::class, inversedBy: 'movies')]
    #[Groups(["getMovies"])]
    private Collection $actors;

    #[ORM\ManyToMany(targetEntity: Writer::class, inversedBy: 'movies')]
    #[Groups(["getMovies"])]
    private Collection $writers;

    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'movies')]
    #[Groups(["getMovies"])]
    private Collection $genres;

    public function __construct()
    {
        $this->actors = new ArrayCollection();
        $this->writers = new ArrayCollection();
        $this->genres = new ArrayCollection();
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

    public function getRated(): ?string
    {
        return $this->rated;
    }

    public function setRated(?string $rated): static
    {
        $this->rated = $rated;

        return $this;
    }

    public function getReleased(): ?\DateTimeInterface
    {
        return $this->released;
    }

    public function setReleased(\DateTimeInterface $released): static
    {
        $this->released = $released;

        return $this;
    }

    public function getRuntime(): ?string
    {
        return $this->runtime;
    }

    public function setRuntime(?string $runtime): static
    {
        $this->runtime = $runtime;

        return $this;
    }

    public function getPlot(): ?string
    {
        return $this->plot;
    }

    public function setPlot(string $plot): static
    {
        $this->plot = $plot;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): static
    {
        $this->poster = $poster;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Actor>
     */
    public function getActors(): Collection
    {
        return $this->actors;
    }

    public function addActor(Actor $actor): static
    {
        if (!$this->actors->contains($actor)) {
            $this->actors->add($actor);
        }

        return $this;
    }

    public function removeActor(Actor $actor): static
    {
        $this->actors->removeElement($actor);

        return $this;
    }

    /**
     * @return Collection<int, Writer>
     */
    public function getWriters(): Collection
    {
        return $this->writers;
    }

    public function addWriter(Writer $writer): static
    {
        if (!$this->writers->contains($writer)) {
            $this->writers->add($writer);
        }

        return $this;
    }

    public function removeWriter(Writer $writer): static
    {
        $this->writers->removeElement($writer);

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): static
    {
        if (!$this->genres->contains($genre)) {
            $this->genres->add($genre);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): static
    {
        $this->genres->removeElement($genre);

        return $this;
    }
}
