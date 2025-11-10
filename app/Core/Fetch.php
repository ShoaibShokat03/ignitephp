<?php

namespace Ignitephp\Core;

class Fetch
{
    private string $url;
    private array $headers = [];
    private array $params = [];
    private string $method = 'GET';
    private $body = null;
    private int $timeout = 10;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    // Set HTTP method
    public function method(string $method): self
    {
        $this->method = strtoupper($method);
        return $this;
    }

    // Add query parameters
    public function params(array $params): self
    {
        $this->params = array_merge($this->params, $params);
        return $this;
    }

    // Add headers
    public function headers(array $headers): self
    {
        foreach ($headers as $key => $value) {
            $this->headers[] = "$key: $value";
        }
        return $this;
    }

    // Set request body (JSON or raw)
    public function body($data): self
    {
        if (is_array($data)) {
            $this->body = json_encode($data);
            $this->headers(['Content-Type' => 'application/json']);
        } else {
            $this->body = $data;
        }
        return $this;
    }

    // Set timeout in seconds
    public function timeout(int $seconds): self
    {
        $this->timeout = $seconds;
        return $this;
    }

    // Execute request
    public function send(): array
    {
        $ch = curl_init();

        // Build final URL with query params
        $finalUrl = $this->url;
        if (!empty($this->params)) {
            $query = http_build_query($this->params);
            $finalUrl .= (strpos($finalUrl, '?') === false ? '?' : '&') . $query;
        }

        // cURL options for optimization
        curl_setopt_array($ch, [
            CURLOPT_URL => $finalUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTPHEADER => $this->headers,
            CURLOPT_CUSTOMREQUEST => $this->method
        ]);

        // Attach body for POST, PUT, DELETE
        if (in_array($this->method, ['POST', 'PUT', 'PATCH', 'DELETE']) && $this->body !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->body);
        }

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'status' => $statusCode,
            'error'  => $error ?: null,
            'body'   => $response,
            'json'   => $this->isJson($response) ? json_decode($response, true) : null
        ];
    }

    // Helper to detect JSON response
    private function isJson($string): bool
    {
        json_decode($string);
        return (json_last_error() === JSON_ERROR_NONE);
    }
}

