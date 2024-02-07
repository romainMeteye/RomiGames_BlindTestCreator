<?php

namespace App\Entity;

use App\Repository\AnswersRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnswersRepository::class)]
class Answers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $Answer = [];

    #[ORM\ManyToOne(inversedBy: 'answers')]
    private ?Questions $Questions = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $User = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnswer(): array
    {
        return $this->Answer;
    }

    public function setAnswer(array $Answer): static
    {
        $this->Answer = $Answer;

        return $this;
    }

    public function getQuestions(): ?Questions
    {
        return $this->Questions;
    }

    public function setQuestions(?Questions $Questions): static
    {
        $this->Questions = $Questions;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }
}
