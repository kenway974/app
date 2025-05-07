<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    //TASK ID
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // TITLE
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 25)]
    private ?string $title = null;

    // SOUS-TACHES
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(min: 3, max: 255)]
    private ?string $sous_taches = null;

    // DATE BUTOIR
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    #[Assert\Range(min: 'now', max: '+ 1 month')]
    private ?\DateTimeInterface $date_butoir = null;

    // IMPORTANCE
    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Range(min: 1, max: 5)]
    private ?int $importance = null;

    // CHECKED
    #[ORM\Column]
    private ?bool $checked = null;

    // USER ASSOCIE
    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    // STATS ASSOCIEES
    /**
     * @var Collection<int, Stat>
     */
    #[ORM\ManyToMany(targetEntity: Stat::class, mappedBy: 'Task')]
    private Collection $stats;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'tasks')]
    private Collection $categories;

    #[ORM\ManyToOne(inversedBy: 'task')]
    private ?Goal $goal = null;


    public function __construct()
    {
        $this->stats = new ArrayCollection();
        $this->categories = new ArrayCollection();
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

    public function getSousTaches(): ?string
    {
        return $this->sous_taches;
    }

    public function setSousTaches(?string $sous_taches): static
    {
        $this->sous_taches = $sous_taches;

        return $this;
    }

    public function getDateButoir(): ?\DateTimeInterface
    {
        return $this->date_butoir;
    }

    public function setDateButoir(\DateTimeInterface $date_butoir): static
    {
        $this->date_butoir = $date_butoir;

        return $this;
    }

    public function getImportance(): ?int
    {
        return $this->importance;
    }

    public function setImportance(int $importance): static
    {
        $this->importance = $importance;

        return $this;
    }

    public function isChecked(): ?bool
    {
        return $this->checked;
    }

    public function setChecked(bool $checked): static
    {
        $this->checked = $checked;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Stat>
     */
    public function getStats(): Collection
    {
        return $this->stats;
    }

    public function addStat(Stat $stat): static
    {
        if (!$this->stats->contains($stat)) {
            $this->stats->add($stat);
            $stat->addTask($this);
        }

        return $this;
    }

    public function removeStat(Stat $stat): static
    {
        if ($this->stats->removeElement($stat)) {
            $stat->removeTask($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function getGoal(): ?Goal
    {
        return $this->goal;
    }

    public function setGoal(?Goal $goal): static
    {
        $this->goal = $goal;

        return $this;
    }
 
}
