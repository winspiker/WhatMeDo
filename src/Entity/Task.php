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
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'string', enumType: ProgressValue::class)]
    private ProgressValue $status = ProgressValue::InProgress;

    #[ORM\Column(length: 255)]
    private User $creator;

    public function __construct(User $creator, string $title, ?string $description = null)
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
        assert(mb_strlen($title) >= 5 && mb_strlen($title) < 50, 'Title is not valid.');

        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function changeDescription(string $description): self
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
}
