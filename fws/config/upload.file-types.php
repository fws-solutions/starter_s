<?php
/**
 * Return list of file-types that we want to explicitly allow or disallow in "Media" dashboard page
 */

declare(strict_types=1);

return [
    // specify additional file-types to upload (as "extension" => "mime-type")
    'allow' => [
        'pdf' => 'application/pdf',     // PDF files
        'dwg' => 'image/vnd.dwg',       // autocad files
    ],
    // specify file-types that we want to forbid (as list of file-extensions)
    'forbid' => [
        'bmp',      // uncompressed image files
        'wav',      // uncompressed audio files
    ],
];
