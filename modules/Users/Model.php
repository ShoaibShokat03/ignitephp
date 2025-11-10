<?php

namespace App\Modules\Users;

use App\Core\Model as BaseModel;

/**
 * Users Model
 * 
 * Uses ActiveRecord pattern - no CRUD methods needed!
 * 
 * Usage:
 * - Model::all() - Get all records
 * - Model::where('status', 'active')->all() - Query with conditions
 * - Model::find(1) - Find by ID
 * - Model::create(['name' => 'John']) - Create new record
 * - $model->save() - Update record
 * - $model->delete() - Delete record
 * 
 * See ACTIVERECORD_USAGE.md for more examples
 */
class Model extends BaseModel
{
    protected string $table = 'users';

    // Model fields - add your table fields here
    // Example:
    // public $id;
    // public $name;
    // public $email;
    // public $created_at;
}
