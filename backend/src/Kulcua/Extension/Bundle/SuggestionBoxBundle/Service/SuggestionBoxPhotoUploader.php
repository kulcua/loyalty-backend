<?php

declare(strict_types=1);

namespace Kulcua\Extension\Bundle\SuggestionBoxBundle\Service;

use Gaufrette\Filesystem;
use Kulcua\Extension\Component\SuggestionBox\Domain\Model\SuggestionBoxFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class SuggestionBoxPhotoUploader.
 */
class SuggestionBoxPhotoUploader
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * FileUploader constructor.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param string|null $path
     *
     * @return string
     */
    public function get(string $path = null): string
    {
        if (null === $path) {
            return '';
        }

        return $this->filesystem->get($path)->getContent();
    }

    /**
     * @param UploadedFile $src
     *
     * @return SuggestionBoxFile
     */
    public function upload(UploadedFile $src): SuggestionBoxFile
    {
        $fileName = md5(uniqid()).'.'.$src->guessExtension();
        $file = new SuggestionBoxFile(
            'suggestion_box'.DIRECTORY_SEPARATOR.$fileName,
            $src->getClientOriginalName(),
            $src->getClientMimeType()
        );
        $this->filesystem->write($file->getPath(), file_get_contents($src->getRealPath()));
        unlink($src->getRealPath());

        return $file;
    }

    /**
     * @param string|null $path
     */
    public function remove(string $path = null): void
    {
        if (null === $path) {
            return;
        }

        if ($this->filesystem->has($path)) {
            $this->filesystem->delete($path);
        }
    }
}
