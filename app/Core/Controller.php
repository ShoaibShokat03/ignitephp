<?php

namespace App\Core;

use Ignitephp\Core\Request;

/**
 * Base Controller Class
 * All controllers should extend this class
 */
abstract class Controller
{
    /**
     * Send JSON response
     */
    protected function json(array $data, int $statusCode = 200): array
    {
        http_response_code($statusCode);
        return $data;
    }

    /**
     * Send success response
     */
    protected function success(array $data = [], string $message = 'Success', int $statusCode = 200): array
    {
        return $this->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Send error response
     */
    protected function error(string $message = 'Error', array $errors = [], int $statusCode = 400): array
    {
        return $this->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }

    /**
     * Get request data
     */
    protected function getRequestData()
    {
        return Request::getJson() ?? Request::getBody();
    }
}

