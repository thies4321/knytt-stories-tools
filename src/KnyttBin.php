<?php

declare(strict_types=1);

namespace KnyttStoriesTools;

use KnyttStoriesTools\Exception\FileNotFound;
use KnyttStoriesTools\Exception\MissingHeader;

use function array_keys;
use function array_pop;
use function array_unshift;
use function explode;
use function fclose;
use function file_exists;
use function file_put_contents;
use function filesize;
use function fopen;
use function fread;
use function fseek;
use function ftell;
use function implode;
use function is_numeric;
use function ltrim;
use function mkdir;
use function ord;
use function rtrim;
use function sprintf;
use function str_ends_with;
use function str_starts_with;
use function strpos;
use function substr;
use function trim;

use const PHP_EOL;
use const SEEK_CUR;

final readonly class KnyttBin
{
    private string $rootDir;
    private int $size;
    private array $files;

    /**
     * @throws FileNotFound
     * @throws MissingHeader
     */
    public function __construct(string $file, bool $skipSongs = true)
    {
        if (! file_exists($file)) {
            throw FileNotFound::forBinFile($file);
        }

        $binSize = filesize($file);
        $rootDir = '';
        $files = [];

        $handle = fopen($file, 'r');

        if (fread($handle, 2) !== 'NF') {
            throw MissingHeader::forNfHeader($file);
        }

        while(ord($chr = fread($handle, 1))) {
            $rootDir .= $chr;
        }

        fread($handle, 4);

        while(ftell($handle) < $binSize)
        {
            fread($handle, 2);

            $filePath = '';
            while(ord($chr = fread($handle, 1))) {
                $filePath .= ($chr == '\\') ? '/' : $chr;
            }

            $sizeBytes = fread($handle, 4);
            $files[$filePath]['size'] =
                ord($sizeBytes[0]) +
                ord($sizeBytes[1])*256 +
                ord($sizeBytes[2])*65536 +
                ord($sizeBytes[3])*16777216;

            if($skipSongs === false) {
                $files[$filePath]['data'] = fread($handle, $files[$filePath]['size']);
                continue;
            }

            if(
                str_starts_with($filePath, 'Music') === false &&
                str_starts_with($filePath, 'Ambiance') === false &&
                str_ends_with($filePath, '.ogg') === false
            ) {
                $files[$filePath]['data'] = fread($handle, $files[$filePath]['size']);
                continue;
            }

            $files[$filePath]['data'] = fseek($handle, $files[$filePath]['size'], SEEK_CUR);
        }

        fclose($handle);

        $this->rootDir = $rootDir;
        $this->size = $binSize;
        $this->files = $files;
    }

    public function getRootDir(): string
    {
        return $this->rootDir;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function getFileNames(): array
    {
        return array_keys($this->files);
    }

    public function getFile(string $file): ?array
    {
        return $this->files[$file] ?? null;
    }

    /**
     * @throws FileNotFound
     */
    public function storeFileToDisk(string $file, string $targetDirectory): void
    {
        if (! isset($this->files[$file])) {
            throw FileNotFound::forWithinBinFile($file);
        }

        $fileParts = explode('/', $file);
        $fileName = array_pop($fileParts);

        if (! empty($fileParts)) {
            array_unshift($fileParts, rtrim($targetDirectory, '/'));
            $targetDirectory = implode('/', $fileParts);
        }

        if (! file_exists($targetDirectory)) {
            mkdir($targetDirectory, 0777, true);
        }

        file_put_contents(sprintf('%s/%s', rtrim($targetDirectory), $fileName), $this->files[$file]['data']);
    }

    public function readINI($file = 'World.ini'): ?array
    {
        //File not found, quit
        if(!isset($this->files[$file])) {
            return null;
        }

        $lines = explode(PHP_EOL, $this->files[$file]['data']);
        $result = [];
        $header = null;

        foreach ($lines as $line) {
            $line = trim($line);

            if (!$line || $line[0] == "; ") {
                $header = null;
                continue;
            }

            if (str_starts_with($line, '[') && str_ends_with($line, ']')) {
                $header = substr($line, 1, -1);

                if (! isset($result[$header])) {
                    $result[$header] = [];
                }

                continue;
            }

            if (! strpos($line, '=')) {
                continue;
            }

            $tmp = explode('=', $line, 2);

            if ($header !== null) {
                $result[$header][trim($tmp[0])] = $this->parseINIValue(ltrim($tmp[1]));
                continue;
            }

            $result[trim($tmp[0])] = $this->parseINIValue(ltrim($tmp[1]));
        }

        return $result;
    }

    private function parseINIValue(string $value): int|float|bool|string
    {
        if (is_numeric($value)) {
            if (strpos($value, '.')) {
                return (float) $value;
            }

            return (int) $value;
        }

        if ($value === 'True') {
            return true;
        }

        if ($value === 'False') {
            return false;
        }

        return $value;
    }
}
