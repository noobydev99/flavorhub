<?php
namespace FlavorHub\Integration;

use Exception;

/**
 * File Uploader (Integration Layer)
 * Validates and uploads images securely.
 */
class FileUploader {
    private string $targetDir;
    private array $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    private array $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    private int $maxSize; // in bytes

    public function __construct(string $targetDir = __DIR__ . '/../../uploads/', int $maxSize = 5242880) { // 5MB default
        $this->targetDir = rtrim($targetDir, '/') . '/';
        $this->maxSize = $maxSize;

        // Create uploads directory if it doesn't exist
        if (!is_dir($this->targetDir)) {
            mkdir($this->targetDir, 0755, true);
        }
    }

    /**
     * Upload an image file.
     *
     * @param array $file The $_FILES['image'] array
     * @return string The relative path of the uploaded file
     * @throws Exception
     */
    public function uploadImage(array $file): string {
        if (!isset($file['error']) || is_array($file['error'])) {
            throw new Exception("Invalid file upload parameters.");
        }

        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new Exception("No file was sent.");
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new Exception("Exceeded file size limits.");
            default:
                throw new Exception("Unknown upload error occurred.");
        }

        // Check size
        if ($file['size'] > $this->maxSize) {
            throw new Exception("File size exceeds limit (5MB).");
        }

        // Check MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $this->allowedMimeTypes)) {
            throw new Exception("Invalid file type. Allowed formats: JPG, PNG, GIF, WEBP.");
        }

        // Check file extension
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $this->allowedExtensions)) {
            throw new Exception("Invalid file extension.");
        }

        // Generate unique filename to prevent overwrites and security issues
        $filename = sprintf('%s.%s', sha1_file($file['tmp_name']) . '_' . uniqid('', true), $ext);
        
        // Prevent path traversal
        $destPath = $this->targetDir . basename($filename);

        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            throw new Exception("Failed to save uploaded file.");
        }

        return 'uploads/' . basename($filename);
    }
}
