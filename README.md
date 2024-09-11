# knytt-stories-tools
Knytt Stories tools written in PHP. Currently only has a .knytt.bin parser. 

## Basic usage

```php
$bin = new \KnyttStoriesTools\KnyttBin('/path/to/file.knytt.bin');
$bin->getRootDir() // (string) Nifflas - Tutorial
$bin->getSize() // (int) 45452
```

## Get filenames from bin file

```php
$bin = new \KnyttStoriesTools\KnyttBin('/path/to/file.knytt.bin');
$bin->getFileNames();
```

output example:
```php
[
    "DefaultSavegame.ini",
    "Icon.png",
    "Info.png",
    "Map.bin",
    "World.ini",
]
```

## Get data for single file from bin file
```php
$bin = new \KnyttStoriesTools\KnyttBin('/path/to/file.knytt.bin');
$bin->getFile('Icon.png');
```

output example:
```php
[
    "size" => 662,
    "data" => b"ëPNG\r\n\x1A\n\0\0\0\rIHDR\0\0\0\x1E\0\0\0\x1E\x08\x06\0\0\0;0«ó\0\0\0\tpHYs\0\0\v\x13\0\0\v\x13\x01\0Ü£\x18\0\0\0\x04gAMA\0\0▒Ä|¹Qô\0\0\0 cHRM\0\0z%\0\0Çâ\0\0¨ \0\0ÇÚ\0\0u0\0\0Û`\0\0:ÿ\0\0\x17oÆ_┼F\0\0\x02\fIDATx┌b³  ?\x03.└╚╚(╔@$\0Ü¾£ü\x04\0\x10@,x,ò\x05R┬@\Fä9]@§,@╦\x1F\x13k1@\01Ô‗1ð / Á\x15─¥x±"N\x03¶§§aLoáY█êÁ\x18 ÇÿpX¬CäE$ÚC\x07\0\x01─äC\\x0Eõ█ªª&¼ûó[\x0EUÀ\x15¬Å(\0\x10@L°$ıııQ,à\x059êFÂ▄┴┴üüT\0\x10@L─*DÅg|±N\f\0\x08 &å\x01\x02\0\x01D▓┼ö·\x14\x06\0\x02êà\x18E°R2╣\0 Çê▓©¼\fw\x19ÊııEû┼\0\x01äÁ\0A.<­Yè┼ró\v\x11Ç\0┬\eÃQQQD╣×Xu╚\0 Ç@à;\x06\x06\x02P\t\x04‗§\x7F\x120H¢\x0E6¾░aÇ\0b\x04*V\x01b\x1E û\x02ÔG@┴+H┼ƒ\x1C\t~ V´3 ■\x02\x10@á─\x15\x01─Z@\x1C\tè#á&\x06ÿ\x01P░ò\x08K¢Ð╩k9<·û\x03±5Ç\0büÕKhû┘\n5õ\n▓f"j'Æ§\x01\x04\x10¦j't}\0\x01─äºû┴¿Øp\x01b¶íW,\0\x01─ä»ûA»Øp¨ÇÉ>\x18FÍ\x07\x10@,─û¢╚.F«"I)█æ§\x01\x04\x10Iò\x04r}L*@w,@\0Ð¡vBÎ\x07\x10@,─È<õÍN°¶\x01\x04\x10\v¼\x12└W╦É[;ßË\x07\x10@L╚èýýýP$├├├\tÍNõÛ\x03\x08 &|Á\f╣Á\x131·\0\x02\x08TIÈ òı░r¸\x11ü‗\x16WyMî>pY\r\x10@╚Á\x13\f╝\x05uEÉ║0─\x02R¶}\x01\x08 F|Ø6Z\x02Ç\0\x03\0n╗±ðô¡8\x1F\0\0\0\0IEND«B`é",
]

```

## Store file from bin to disk
```php
$bin = new \KnyttStoriesTools\KnyttBin('/path/to/file.knytt.bin');
$bin->storeFileToDisk('Icon.png', '/my/desired/directory');
```

## Parse INI within bin file to array
```php
$bin = new \KnyttStoriesTools\KnyttBin('/path/to/file.knytt.bin');
$bin->readINI('World.ini') // Defaults to "World.ini" when left empty
```

output example:
```php
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
```
