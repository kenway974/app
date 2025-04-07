<?php

namespace App\Entity;

use App\Repository\StatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StatRepository::class)]
class Stat
{
    // STAT ID
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // TITLE
    #[ORM\Column(length: 25)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 25)]
    private ?string $title = null;

    //SCORE
    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Range(min: 0, max: 100)]
    private ?int $score = null;

    // DESCRIPTION
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(min: 3, max: 255)]
    private ?string $description = null;

    // USER ASSOCIE
    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'stats')]
    private Collection $User;

    // TACHES ASSOCIEES
    /**
     * @var Collection<int, Task>
     */
    #[ORM\ManyToMany(targetEntity: Task::class, inversedBy: 'stats')]
    private Collection $Task;

    public function __construct()
    {
        $this->User = new ArrayCollection();
        $this->Task = new ArrayCollection();
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

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->User;
    }

    public function addUser(User $user): static
    {
        if (!$this->User->contains($user)) {
            $this->User->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->User->removeElement($user);

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTask(): Collection
    {
        return $this->Task;
    }

    public function addTask(Task $task): static
    {
        if (!$this->Task->contains($task)) {
            $this->Task->add($task);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        $this->Task->removeElement($task);

        return $this;
    }
}
