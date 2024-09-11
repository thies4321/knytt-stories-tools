<?php

declare(strict_types=1);

namespace KnyttStoriesTools\Tests;

use KnyttStoriesTools\Exception\FileNotFound;
use KnyttStoriesTools\Exception\MissingHeader;
use KnyttStoriesTools\KnyttBin;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function sprintf;

final class KnyttBinTest extends TestCase
{
    /**
     * @throws FileNotFound
     * @throws MissingHeader
     */
    private function getKnyttBin(string $fileName, bool $skipSongs = true): KnyttBin
    {
        return new KnyttBin(sprintf('%s/../Fixtures/%s.bin', __DIR__, $fileName), $skipSongs);
    }

    /**
     * @throws FileNotFound
     * @throws MissingHeader
     */
    public function testParseSuccessful(): void
    {
        $bin = $this->getKnyttBin('test');

        $this->assertEquals('Nifflas - Tutorial', $bin->getRootDir());
        $this->assertEquals(45452, $bin->getSize());
    }

    /**
     * @throws FileNotFound
     * @throws MissingHeader
     */
    public function testFileNotFound(): void
    {
        $this->expectException(FileNotFound::class);

        $this->getKnyttBin('file_not_found');
    }

    /**
     * @throws FileNotFound
     * @throws MissingHeader
     */
    public function testMissingHeader(): void
    {
        $this->expectException(MissingHeader::class);

        $this->getKnyttBin('invalid_file');
    }

    public static function parsedIniProvider(): ?array
    {
        return [
            'world_ini' => [
                'World.ini',
                [
                    "World" => [
                        "Name" => "Tutorial",
                        "Size" => "Small",
                        "Difficulty A" => "Easy",
                        "Category A" => "Tutorial",
                        "Author" => "Nifflas",
                        "Description" => "Learn how to play Knytt Stories!",
                        "Format" => 0,
                    ],
                    "x1000y1000" => [
                        "Sign(A)" => "Welcome! Try to walk around by pressing the left and right arrow keys.",
                    ],
                    "x1001y1000" => [
                        "Sign(A)" => "Press S to jump!",
                    ],
                    "x1002y1000" => [
                        "Sign(A)" => "This power-up enables you to run! If you still prefer to walk, hold down A.",
                    ],
                    "x1003y1000" => [
                        "Sign(A)" => "This power-up enables you to climb walls by pressing the up arrow.",
                    ],
                    "x1004y1000" => [
                        "Sign(A)" => "Here, you need to jump from a wall! Press S and then the right arrow quickly.",
                    ],
                    "x1005y1000" => [
                        "Sign(A)" => "Stand on the white light and press the down arrow to save your game!",
                    ],
                    "x1006y1000" => [
                        "Sign(A)" => "This power-up enables you to make higher jumps by holding the S key longer.",
                    ],
                    "x1007y1000" => [
                        "Sign(A)" => "With the double jump power-up, you can jump in the air! Try pressing the S button twice.",
                    ],
                    "x1008y998" => [
                        "Sign(A)" => "You can slow your fall with the umbrella. Press the D button to toggle it.",
                    ],
                    "x1009y998" => [
                        "Sign(A)" => "Remember that you can double jump when using the umbrella!",
                    ],
                    "x1010y998" => [
                        "Sign(A)" => "Hold down Q to see which items you have collected.",
                    ],
                    "x1011y999" => [
                        "Sign(A)" => "With this power-up, you can see many invisible things.",
                    ],
                    "x1010y999" => [
                        "Sign(A)" => "Nothing invisible here! Try going back to the place you just came from and take a look around.",
                    ],
                    "x1012y998" => [
                        "Sign(A)" => "Having this detector will make you glow red when an enemy approaches.",
                    ],
                    "x1013y998" => [
                        "Sign(A)" => "The detector does not work on robots or other mechanical devices.",
                    ],
                    "x1014y998" => [
                        "Sign(A)" => "This power-up allows you to create a hologram. Quickly press the down arrow twice, or press W.",
                    ],
                    "x1015y998" => [
                        "Sign(A)" => "When the hologram is in use, enemies will see the hologram instead of you.",
                    ],
                    "x1016y998" => [
                        "Sign(A)" => "...and that is how you play Knytt Stories.",
                    ],
                    "x1004y999" => [
                        "Sign(A)" => "Congratulations! There are secrets to be found by those with a sharp eye.",
                    ],
                ]
            ],
            'default_save_game_ini' => [
                'DefaultSavegame.ini',
                [
                    "Positions" => [
                        "X Map" => 1000,
                        "Y Map" => 1000,
                        "X Pos" => 12,
                        "Y Pos" => 8,
                    ],
                    "Powers" => [
                        "Power0" => 0,
                        "Power1" => 0,
                        "Power2" => 0,
                        "Power3" => 0,
                        "Power4" => 0,
                        "Power5" => 0,
                        "Power6" => 0,
                        "Power7" => 0,
                        "Power8" => 0,
                        "Power9" => 0,
                        "Power10" => 0,
                        "Power11" => 0,
                    ],
                ]

            ],
            'file_not_found' => [
                'FileNotFound.ini',
                null
            ]
        ];
    }

    /**
     * @throws FileNotFound
     * @throws MissingHeader
     */
    #[DataProvider('parsedIniProvider')]
    public function testReadIni(string $fileName, ?array $expectedResult)
    {
        $bin = $this->getKnyttBin('test');
        $result = $bin->readINI($fileName);

        $this->assertEquals($expectedResult, $result);
    }
}
