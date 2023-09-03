<?php

namespace App\services;

class FileUploader
{
    private $uploadDir;

    public function __construct($uploadDir)
    {
        $this->uploadDir = $uploadDir;
    }

    public function upload($file)
    {
        if ($file['size'] > 5000000 || !in_array($file['type'], ['image/jpeg', 'image/png'])) {
            throw new \Exception('Invalid file size or type.');
        }

        if (!is_uploaded_file($file['tmp_name'])) {
            throw new \Exception('File upload error.');
        }

        $targetFile = $this->uploadDir . time() . '_' . basename($file['name']);
        if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
            throw new \Exception('Failed to move uploaded file.');
        }

        return $targetFile;
    }
    public function generateFileUrl($fullFilePath) {
        $relativePath = strstr($fullFilePath, 'media\products\thumbnails');
        return str_replace('\\', '/', $relativePath);
    }
}