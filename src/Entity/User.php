<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use App\Types\Password;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
final class User implements UserInterface
{
    use TimestampableEntity;

    #[ORM\Id, ORM\Column]
    private string $id;

    #[ORM\Column]
    private array $roles;

    #[ORM\Column(type: 'password')]
    private Password $password;

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Task::class)]
    private Collection $tasks;


    public function __construct(
        #[ORM\Column(length: 180, unique: true)]
        private readonly string $email,
        string $password
    ) {
        $this->id = uniqid('user_', true);
        $this->password = new Password($password);
        $this->tasks = new ArrayCollection();
        $this->roles = ['ROLE_USER'];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTasks(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setCreator($this);
        }

        return $this;
    }

    public function removeTasks(Task $task): self
    {
        if ($this->tasks->removeElement($task) && $task->getCreator() === $this) {
            $task->setCreator(null);
        }

        return $this;
    }


}
