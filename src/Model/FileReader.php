<?php
/**
 * @copyright Copyright (c) Mateusz Wira (m.wirson@gmail.com)
 */

declare(strict_types=1);

namespace App\Model;

class FileReader
{
    public function readFile(string $fileName): array
    {
        return explode("\n", file_get_contents($fileName));
    }
}
