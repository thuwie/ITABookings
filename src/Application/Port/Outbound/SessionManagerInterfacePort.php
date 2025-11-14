<?php
namespace App\Application\Port\Outbound;
interface SessionManagerInterfacePort
{
    public function start(): void;
    public function has(string $key): bool;
    public function get(string $key);
    public function set(string $key, mixed $value): void;
    public function remove(string $key): void;
    public function destroy(): void;
}
