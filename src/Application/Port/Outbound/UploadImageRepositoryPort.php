<?php
namespace App\Application\Port\Outbound;

use Psr\Http\Message\UploadedFileInterface;

interface UploadImageRepositoryPort {
    public function saveOne(UploadedFileInterface $file, string $category, string $nameFolder): string;
    public function saveMultipleOnes(array $files, string $category, string $nameFolder): array;
}
