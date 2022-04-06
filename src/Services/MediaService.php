<?php

declare(strict_types=1);

namespace App\Services;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;

class MediaService
{
    private const STORAGE_DIR = 'images/uploads';

    public function __construct(
        private KernelInterface $kernel,
        private Filesystem $filesystem,
    ) {
    }

    public function putFileToUploadsFolderAs(UploadedFile $file, string $name): string
    {
        $uploads_path = self::STORAGE_DIR.'/'.$name;
        $full_path = $this->getPublicPath().'/'.$uploads_path;

        $this->filesystem->copy($file->getRealPath(), $full_path);

        return '/'.$uploads_path;
    }

    public function deleteFromUploadsFolder(string $filename): void
    {
        $this->filesystem->remove($this->getPublicPath().$filename);
    }

    private function getPublicPath(): string
    {
        return $this->kernel->getProjectDir().'/public';
    }
}
