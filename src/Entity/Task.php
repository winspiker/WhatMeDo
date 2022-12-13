<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TaskRepository;
use App\Value\ProgressValue;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    use TimestampableEntity;

    #[ORM\Id, ORM\Column]
    private string $id;

    #[ORM\Column(length: 50)]
    private string $title;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description;

    #[ORM\Column(type: 'string', enumType: ProgressValue::class)]
    private ProgressValue $status = ProgressValue::InProgress;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private User $creator;

    public function __construct($creator, string $title, ?string $description = null)
    {
        $this->id = uniqid('task_', true);
        $this->creator = $creator;
        $this->title = $title;
        $this->description = $description;
    }

    public function getId(): string
    {
        return $this->id;
    }


    public function changeTitle(string $title): self
    {
        $this->title = $title;

        if(mb_strlen($title) < 5 || mb_strlen($title) > 50) {
            throw new \InvalidArgumentException();
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function changeDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?ProgressValue
    {
        return $this->status;
    }

    public function done(): self
    {
        $this->status = ProgressValue::Done;

        return $this;
    }

    public function getCreator(): User
    {
        return $this->creator;
    }

    public function setCreator(User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
