<?php

declare(strict_types=1);

namespace KnyttStoriesTools\Exception;

use Exception;

use function sprintf;

final class FileNotFound extends Exception
{
    public static function forBinFile(string $path): self
    {
        return new self(sprintf('Bin file not found for path [%s]', $path));
    }

    public static function forWithinBinFile(string $file): self
    {
        return new self(sprintf('File [%s] not found in bin directory structure', $file));
    }
}
