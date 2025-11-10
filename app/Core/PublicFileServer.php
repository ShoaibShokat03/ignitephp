<?php

namespace App\Core;

/**
 * Public File Server
 * Serves files from the public folder
 * URLs like /images/img.png will serve /public/images/img.png
 */
class PublicFileServer
{
    protected string $publicPath;
    protected string $baseUrl;

    public function __construct(string $publicPath = null)
    {
        // Use absolute path to ensure we can find the public folder
        if ($publicPath === null) {
            // Get the project root (where index.php is located)
            $projectRoot = dirname(dirname(dirname(__DIR__)));
            $this->publicPath = $projectRoot . '/public';
        } else {
            $this->publicPath = $publicPath;
        }
        
        // Normalize the path
        $this->publicPath = str_replace('\\', '/', $this->publicPath);
        $this->baseUrl = $this->getBaseUrl();
    }

    /**
     * Get the base URL of the application
     */
    protected function getBaseUrl(): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $scriptName = dirname($_SERVER['SCRIPT_NAME'] ?? '');
        
        // Remove /api if present in script name
        $scriptName = rtrim(str_replace('/api', '', $scriptName), '/');
        
        return $protocol . $host . $scriptName;
    }

    /**
     * Check if the request is for a public file
     * Supports files in any location within public folder
     * Example: public/me.jfif -> /me.jfif
     * Example: public/images/logo.png -> /images/logo.png
     */
    public function isPublicFileRequest(string $uri): bool
    {
        // Remove query string
        $path = parse_url($uri, PHP_URL_PATH);
        if (empty($path) || $path === '/') {
            return false;
        }

        // Normalize the path
        $normalizedPath = $this->normalizeRequestPath($path);
        
        if (empty($normalizedPath)) {
            return false;
        }
        
        // Ensure public path exists
        $realPublicPath = realpath($this->publicPath);
        if ($realPublicPath === false) {
            return false;
        }
        
        // Build the full file path
        $filePath = $realPublicPath . '/' . $normalizedPath;
        
        // Normalize file path separators
        $filePath = str_replace('\\', '/', $filePath);
        
        // Get real path of the requested file
        $realFilePath = realpath($filePath);
        
        // Security check: file must be within public folder
        if ($realFilePath === false) {
            return false;
        }
        
        // Normalize for comparison
        $realFilePath = str_replace('\\', '/', $realFilePath);
        $realPublicPath = str_replace('\\', '/', $realPublicPath);
        
        // Ensure the file is within the public directory
        if (strpos($realFilePath, $realPublicPath) !== 0) {
            return false;
        }
        
        // Must be a file (not a directory)
        return is_file($realFilePath);
    }

    /**
     * Normalize the request path to remove base path and /api prefix
     */
    protected function normalizeRequestPath(string $path): string
    {
        // Start with the original path
        $normalized = $path;
        
        // Handle /api/ prefix first (most common case)
        if (strpos($normalized, '/api/') === 0) {
            $normalized = substr($normalized, 5); // Remove '/api/'
        } elseif ($normalized === '/api') {
            return ''; // Not a file request
        }
        
        // Get script name and base path
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = dirname($scriptName);
        
        // Normalize base path
        $basePath = rtrim(str_replace('\\', '/', $basePath), '/');
        
        // Remove /api from base path if present
        if (strpos($basePath, '/api') !== false) {
            $basePath = str_replace('/api', '', $basePath);
        }
        
        // Only remove base path if it's meaningful and matches
        // Skip if basePath is empty, '/', '.', or the normalized path doesn't start with it
        if (!empty($basePath) && $basePath !== '/' && $basePath !== '.') {
            // Check if normalized path starts with basePath
            if (strpos($normalized, $basePath) === 0) {
                // Remove the basePath
                $normalized = substr($normalized, strlen($basePath));
                // Remove leading slash if present
                $normalized = ltrim($normalized, '/');
            }
        }
        
        // Clean up the path - remove leading slash
        $normalized = ltrim($normalized, '/');
        
        return $normalized;
    }

    /**
     * Serve a public file
     */
    public function serveFile(string $uri): bool
    {
        if (!$this->isPublicFileRequest($uri)) {
            return false;
        }

        // Remove query string and normalize path
        $path = parse_url($uri, PHP_URL_PATH);
        $normalizedPath = $this->normalizeRequestPath($path);
        
        if (empty($normalizedPath)) {
            return false;
        }
        
        // Get real public path
        $realPublicPath = realpath($this->publicPath);
        if ($realPublicPath === false) {
            return false;
        }
        
        // Build file path
        $filePath = $realPublicPath . '/' . $normalizedPath;
        $filePath = str_replace('\\', '/', $filePath);
        $realFilePath = realpath($filePath);

        if ($realFilePath === false || !is_file($realFilePath)) {
            return false;
        }

        // Get MIME type
        $mimeType = $this->getMimeType($realFilePath);
        
        // Set headers
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($realFilePath));
        header('Cache-Control: public, max-age=31536000'); // 1 year cache
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($realFilePath)) . ' GMT');
        
        // Handle If-Modified-Since for caching
        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
            $ifModifiedSince = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
            if ($ifModifiedSince >= filemtime($realFilePath)) {
                http_response_code(304);
                exit;
            }
        }

        // Output file
        readfile($realFilePath);
        return true;
    }

    /**
     * Get MIME type for a file
     */
    protected function getMimeType(string $filePath): string
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        $mimeTypes = [
            // Images
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jfif' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'bmp' => 'image/bmp',
            
            // Documents
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            
            // Text
            'txt' => 'text/plain',
            'html' => 'text/html',
            'htm' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            
            // Archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'tar' => 'application/x-tar',
            'gz' => 'application/gzip',
            
            // Audio
            'mp3' => 'audio/mpeg',
            'wav' => 'audio/wav',
            'ogg' => 'audio/ogg',
            
            // Video
            'mp4' => 'video/mp4',
            'webm' => 'video/webm',
            'avi' => 'video/x-msvideo',
        ];

        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }

    /**
     * Get the base URL
     */
    public function getBaseUrlValue(): string
    {
        return $this->baseUrl;
    }

    /**
     * Get public file URL
     */
    public function getPublicUrl(string $path): string
    {
        $path = ltrim($path, '/');
        return rtrim($this->baseUrl, '/') . '/' . $path;
    }
}

