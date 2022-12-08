<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use DateTimeZone;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

enum ProgressValue: string {
    case InProgress = 'in_progress';
    case Done = 'done';
}




#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    const TIME_ZONE = 'Europe/Kyiv';


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'string', enumType: ProgressValue::class)]
    private ProgressValue $status;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    private \DateTimeImmutable $updatedAt;

    public function __construct()
    {
        $this->status = ProgressValue::InProgress;
        $this->createdAt = new \DateTimeImmutable(timezone: new \DateTimeZone(self::TIME_ZONE));
        $this->updatedAt = new \DateTimeImmutable(timezone: new \DateTimeZone(self::TIME_ZONE));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?ProgressValue
    {
        return $this->status;
    }

    public function doneStatus(): self
    {
        $this->status = ProgressValue::Done;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdated(): self
    {
        $this->updatedAt = new \DateTimeImmutable(timezone: new \DateTimeZone(self::TIME_ZONE));

        return $this;
    }
}
