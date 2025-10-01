<?php
if (!class_exists('Redis')) {
    class Redis {
        public function connect(string $host, int $port): bool { return true; }
        public function pconnect(string $host, int $port): bool { return true; }
        public function get(string $key): mixed { return null; }
        public function set(string $key, mixed $value, ?int $ttl = null): bool { return true; }
        public function del(string $key): int { return 0; }
    }
}
