<?php

declare(strict_types=1);

namespace Kulcua\Extension\Bundle\ChatBundle\Service;

use Gaufrette\Filesystem;
use Kulcua\Extension\Component\Message\Domain\Model\MessageFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class MessagePhotoUploader.
 */
class MessagePhotoUploader
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
     * @return MessageFile
     */
    public function upload(UploadedFile $src): MessageFile
    {
        $fileName = md5(uniqid()).'.'.$src->guessExtension();
        $file = new MessageFile(
            'message'.DIRECTORY_SEPARATOR.$fileName,
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
