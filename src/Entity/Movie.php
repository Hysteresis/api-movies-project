<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
#[ApiResource]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getMovies"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getMovies"])]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getMovies"])]
    private ?string $rated = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(["getMovies"])]
    private ?\DateTimeInterface $released = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getMovies"])]
    private ?string $runtime = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["getMovies"])]
    private ?string $plot = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getMovies"])]
    private ?string $poster = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getMovies"])]
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
