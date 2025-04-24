<?php

namespace App\Extensions\Avatar;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

abstract class AvatarProvider
{
    /**
     * @var array<string, static>
     */
    protected static array $providers = [];

    public static function getProvider(string $id): ?self
    {
        return Arr::get(static::$providers, $id);
    }

    /**
     * @return array<string, static>
     */
    public static function getAll(): array
    {
        return static::$providers;
    }

    public function __construct()
    {
        static::$providers[$this->getId()] = $this;
    }

    abstract public function getId(): string;

    abstract public function get(User $user): ?string;

    public function getName(): string
    {
        return Str::title($this->getId());
    }
}
