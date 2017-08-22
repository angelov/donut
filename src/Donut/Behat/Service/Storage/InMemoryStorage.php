<?php

namespace Angelov\Donut\Behat\Service\Storage;

class InMemoryStorage implements StorageInterface
{
    private $storage = [];

    /** @psalm-suppress MissingReturnType */
    public function get(string $key, $default = null)
    {
        return $this->storage[$key] ?? $default;
    }

    public function set(string $key, $value): void
    {
        $this->storage[$key] = $value;
    }

    public function all(): array
    {
        return $this->storage;
    }

    public function clear(): void
    {
        $this->storage = [];
    }
}
