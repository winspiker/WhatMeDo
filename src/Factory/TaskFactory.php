<?php
declare(strict_types=1);

namespace App\Factory;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Task>
 *
 * @method        Task|Proxy create(array|callable $attributes = [])
 * @method static Task|Proxy createOne(array $attributes = [])
 * @method static Task|Proxy find(object|array|mixed $criteria)
 * @method static Task|Proxy findOrCreate(array $attributes)
 * @method static Task|Proxy first(string $sortedField = 'id')
 * @method static Task|Proxy last(string $sortedField = 'id')
 * @method static Task|Proxy random(array $attributes = [])
 * @method static Task|Proxy randomOrCreate(array $attributes = [])
 * @method static TaskRepository|RepositoryProxy repository()
 * @method static Task[]|Proxy[] all()
 * @method static Task[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Task[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Task[]|Proxy[] findBy(array $attributes)
 * @method static Task[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Task[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class TaskFactory extends ModelFactory
{
    public function __construct(
        private readonly Security $security
    ) {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'title' => self::faker()->text(30),
            'description' => self::faker()->text(100),
            'creator' => $this->security->getUser(),
        ];
    }

    protected static function getClass(): string
    {
        return Task::class;
    }
}
