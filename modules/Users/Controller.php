<?php

namespace App\Modules\Users;

use App\Core\Controller as BaseController;
use App\Modules\Users\Service;

class Controller extends BaseController
{
    protected Service $service;

    public function __construct()
    {
        $this->service = new Service();
    }

    /**
     * Get all Users records
     */
    public function index(): array
    {
        $data = $this->service->getAll();
        return $this->success($data, 'Users records retrieved successfully');
    }

    /**
     * Get Users by ID
     */
    public function show(string $id): array
    {
        $data = $this->service->getById((int)$id);
        
        if ($data) {
            return $this->success($data, 'Users retrieved successfully');
        }

        return $this->error('Users not found', [], 404);
    }

    /**
     * Create new Users
     */
    public function store(): array
    {
        $requestData = $this->getRequestData();
        
        $result = $this->service->create($requestData);
        
        if ($result['success']) {
            return $this->success($result['data'], 'Users created successfully', 201);
        }

        return $this->error($result['message'], $result['errors'] ?? [], 400);
    }

    /**
     * Update Users
     */
    public function update(string $id): array
    {
        $requestData = $this->getRequestData();
        
        $result = $this->service->update((int)$id, $requestData);
        
        if ($result['success']) {
            return $this->success($result['data'], 'Users updated successfully');
        }

        return $this->error($result['message'], $result['errors'] ?? [], 400);
    }

    /**
     * Delete Users
     */
    public function destroy(string $id): array
    {
        $result = $this->service->delete((int)$id);
        
        if ($result['success']) {
            return $this->success([], 'Users deleted successfully');
        }

        return $this->error($result['message'], [], 404);
    }
}