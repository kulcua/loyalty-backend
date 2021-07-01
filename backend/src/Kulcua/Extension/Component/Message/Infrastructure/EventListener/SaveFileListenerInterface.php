<?php

namespace Kulcua\Extension\Component\Message\Infrastructure\EventListener;

/**
 * Class SaveFileListenerInterface.
 */
interface SaveFileListenerInterface
{
    /**
     * @param string $file
     * @param string $realPath
     */
    public function __invoke(string $file, string $realPath): void;
}
