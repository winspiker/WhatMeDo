<?php

declare(strict_types=1);

namespace App\Doctrine\Types;


use App\Value\Password;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Doctrine\Instantiator\Exception\ExceptionInterface;
use Doctrine\Instantiator\Instantiator;

final class PasswordType extends Type
{
    public const PASSWORD = 'password';
    private static ?\ReflectionClass $reflection = null;
    private static ?Instantiator $instantiator = null;


    private static function getInstantiator(): Instantiator
    {
       return self::$instantiator ??= new Instantiator();

    }

    private static function getReflection(): \ReflectionClass
    {
        return self::$reflection ??= new \ReflectionClass(Password::class);

    }


    /**
     * @throws \ReflectionException
     * @throws ExceptionInterface
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): Password
    {
        $instance = self::getInstantiator()->instantiate(Password::class);
        self::getReflection()->getProperty('password')->setValue($instance, $value);

        return $instance;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof Password) {
            $value = $value->getValue();
        }

        return $value;
    }

    public function canRequireSQLConversion(): bool
    {
        return true;
    }

    public function getName(): string
    {
        return self::PASSWORD;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'TEXT';
    }

    public function __destruct()
    {
       self::$instantiator = null;
       self::$reflection = null;
    }
}











