<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sous_taches = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_butoir = null;

    #[ORM\Column]
    private ?int $importance = null;

    #[ORM\Column]
    private ?bool $checked = null;

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
}
