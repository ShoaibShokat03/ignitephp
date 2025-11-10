<?php

namespace App\Modules\Users;

use App\Core\Service as BaseService;
use App\Modules\Users\Model;

class Service extends BaseService
{
    /**
     * Get all Users records
     * Uses ActiveRecord: Model::all()
     */
    public function getAll(): array
    {
        $models = Model::all();
        return array_map(function($model) {
            return $model->toArray();
        }, $models);
    }

    /**
     * Get Users by ID
     * Uses ActiveRecord: Model::find()
     */
    public function getById(int $id): ?array
    {
        $model = Model::find($id);
        return $model ? $model->toArray() : null;
    }

    /**
     * Create Users
     * Uses ActiveRecord: Model::create()
     */
    public function create(array $data): array
    {
        // Add validation here
        $required = ['name']; // Update required fields
        $errors = [];

        foreach ($required as $field) {
            if (empty($data[$field])) {
                $errors[$field] = "The {$field} field is required";
            }
        }

        if (!empty($errors)) {
            return [
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors
            ];
        }

        $model = Model::create($data);

        if ($model) {
            return [
                'success' => true,
                'data' => $model->toArray()
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to create Users'
        ];
    }

    /**
     * Update Users
     * Uses ActiveRecord: $model->save()
     */
    public function update(int $id, array $data): array
    {
        $model = Model::find($id);
        
        if (!$model) {
            return [
                'success' => false,
                'message' => 'Users not found'
            ];
        }

        // Update attributes
        foreach ($data as $key => $value) {
            $model->$key = $value;
        }

        if ($model->save()) {
            return [
                'success' => true,
                'data' => $model->toArray()
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to update Users'
        ];
    }

    /**
     * Delete Users
     * Uses ActiveRecord: $model->delete()
     */
    public function delete(int $id): array
    {
        $model = Model::find($id);
        
        if (!$model) {
            return [
                'success' => false,
                'message' => 'Users not found'
            ];
        }

        if ($model->delete()) {
            return ['success' => true];
        }

        return [
            'success' => false,
            'message' => 'Failed to delete Users'
        ];
    }
}