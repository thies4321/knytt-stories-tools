<?php

declare(strict_types=1);

namespace KnyttStoriesTools\Exception;

use Exception;

use function sprintf;

final class MissingHeader extends Exception
{
    public static function forNfHeader(string $path): self
    {
        return new self(sprintf('NF header not found for file [%s]', $path));
    }
}
