<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;

class ScanViewsFolder
{
    /**
     * Scan folder landings and return array with names files Blade и JPG.
     */
    public static function landings(): array
    {
        $directory = resource_path('views/landings');
        $result = [];

        $items = File::allFiles($directory);

        foreach ($items as $file) {
            $relativePath = str_replace($directory.DIRECTORY_SEPARATOR, '', $file->getPathname());
            $parts = explode(DIRECTORY_SEPARATOR, $relativePath);

            if (count($parts) > 1) {
                $folder = $parts[0];
                $filename = pathinfo($file, PATHINFO_FILENAME);
                $extension = $file->getExtension();

                $viewPath = 'landings.'.str_replace(DIRECTORY_SEPARATOR, '.', str_replace('.blade.php', '', $relativePath));

                if (! isset($result[$folder])) {
                    $result[$folder] = [];
                }

                if ($extension === 'php' && str_ends_with($file->getFilename(), '.blade.php')) {
                    $result[$folder][$filename] = [
                        'view' => $viewPath,
                        'image' => null,
                    ];
                } elseif ($extension === 'jpg') {
                    $webPath = str_replace(resource_path('views'), '/storage', $file->getPathname());
                    if (isset($result[$folder][$filename])) {
                        $result[$folder][$filename]['image'] = $webPath;
                    }
                }
            }
        }

        return $result;
    }

    public static function getLandingsInFolder($folder): array
    {
        $directory = resource_path('views/landings/'.$folder);
        $result = [];

        $items = File::allFiles($directory);

        foreach ($items as $file) {
            $relativePath = str_replace($directory.DIRECTORY_SEPARATOR, '', $file->getPathname());
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $extension = $file->getExtension();

            $viewPath = 'landings.'.$folder.'.'.str_replace(DIRECTORY_SEPARATOR, '.', str_replace('.blade.php', '', $relativePath));

            if ($extension === 'php' && str_ends_with($file->getFilename(), '.blade.php')) {
                $result[$filename] = [
                    'view' => $viewPath,
                    'image' => null,
                ];
            } elseif ($extension === 'jpg') {
                $webPath = str_replace(resource_path('views'), '/storage', $file->getPathname());
                if (isset($result[$filename])) {
                    $result[$filename]['image'] = $webPath;
                }
            }
        }

        return $result;

    }
}
