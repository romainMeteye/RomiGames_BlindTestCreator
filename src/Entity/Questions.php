<?php

namespace App\Entity;

use App\Repository\QuestionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionsRepository::class)]
class Questions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Question = null;

    #[ORM\Column(length: 120)]
    private ?string $response_type = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $desired_answer = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private $filePath;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Forms $Forms = null;

    #[ORM\OneToMany(mappedBy: 'Questions', targetEntity: Answers::class)]
    private Collection $answers;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->Question;
    }

    public function setQuestion(string $Question): static
    {
        $this->Question = $Question;

        return $this;
    }

    public function getResponseType(): ?string
    {
        return $this->response_type;
    }

    public function setResponseType(string $response_type): static
    {
        $this->response_type = $response_type;

        return $this;
    }

    public function getDesiredAnswer(): ?array
    {
        return $this->desired_answer;
    }

    public function setDesiredAnswer(?array $desired_answer): static
    {
        $this->desired_answer = $desired_answer;

        return $this;
    }

    public function getForms(): ?Forms
    {
        return $this->Forms;
    }

    public function setForms(?Forms $Forms): static
    {
        $this->Forms = $Forms;

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): self
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * @return Collection<int, Answers>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answers $answer): static
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setQuestions($this);
        }

        return $this;
    }

    public function removeAnswer(Answers $answer): static
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getQuestions() === $this) {
                $answer->setQuestions(null);
            }
        }

        return $this;
    }
}
