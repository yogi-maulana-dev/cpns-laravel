<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class StorageService
{
    private string $disk;

    public function __construct(string $disk = null)
    {
        $this->disk = $disk ?? config('filesystems.default');
    }

    public function getDisk(): string
    {
        return $this->disk;
    }

    public static function public(): self
    {
        return new self('public');
    }

    public static function local(): self
    {
        return new self('local');
    }

    public function uploadOrReturnDefault(
        string $fileKey,
        ?string $default,
        string $folder
    ): ?string {
        // if no file sent, return the before file name
        if (! request()->hasFile($fileKey)) {
            return $default;
        }

        // if there is file sent, remove a file by the last/before file name
        $default && Storage::disk($this->getDisk())->delete($default);

        // and store new file
        return request()->file($fileKey)->store($folder, [
            'disk' => $this->getDisk(),
        ]);
    }

    public function getStorage(): \Illuminate\Contracts\Filesystem\Filesystem
    {
        return Storage::disk($this->getDisk());
    }
}
