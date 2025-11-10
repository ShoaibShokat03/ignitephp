<?php
/**
 * Model Generator (Improved)
 * Creates a model with field definitions only (no CRUD - uses ActiveRecord)
 * File: Model.php
 */

if ($argc < 3) {
    echo "\n";
    echo "Error: Module name and Model name are required\n";
    echo "\n";
    echo "Usage: php make-model.php <ModuleName> <ModelName> [table-name]\n";
    echo "\n";
    echo "Examples:\n";
    echo "  php make-model.php Product ProductModel\n";
    echo "  php make-model.php Product ProductModel products\n";
    echo "  php make-model.php User UserModel users\n";
    echo "\n";
    exit(1);
}

$moduleName = trim($argv[1]);
$modelName = trim($argv[2]);
$tableName = isset($argv[3]) && !empty(trim($argv[3])) ? trim($argv[3]) : strtolower($modelName);

if (empty($moduleName) || empty($modelName)) {
    echo "\n";
    echo "Error: Module name and Model name cannot be empty\n";
    echo "\n";
    exit(1);
}

// Validate names
if (!preg_match('/^[a-zA-Z0-9_]+$/', $moduleName) || !preg_match('/^[a-zA-Z0-9_]+$/', $modelName)) {
    echo "\n";
    echo "Error: Names can only contain letters, numbers, and underscores\n";
    echo "\n";
    exit(1);
}

// Convert to PascalCase
$moduleName = str_replace('_', ' ', $moduleName);
$moduleName = ucwords(strtolower($moduleName));
$moduleName = str_replace(' ', '', $moduleName);
$moduleName = ucfirst($moduleName);

$modelName = str_replace('_', ' ', $modelName);
$modelName = ucwords(strtolower($modelName));
$modelName = str_replace(' ', '', $modelName);
$modelName = ucfirst($modelName);

$modulePath = __DIR__ . '/../../modules/' . $moduleName;

if (!is_dir($modulePath)) {
    echo "\n";
    echo "Error: Module '{$moduleName}' does not exist.\n";
    echo "\n";
    echo "Create it first using:\n";
    echo "  apimake module {$moduleName}\n";
    echo "\n";
    exit(1);
}

$modelPath = $modulePath . '/Model.php';

if (file_exists($modelPath)) {
    echo "\n";
    echo "Error: Model already exists: {$modelPath}\n";
    echo "\n";
    exit(1);
}

$modelContent = <<<PHP
<?php

namespace App\\Modules\\{$moduleName};

use App\\Core\\Model as BaseModel;

/**
 * {$modelName} Model
 * 
 * Uses ActiveRecord pattern - no CRUD methods needed!
 * 
 * Usage:
 * - Model::all() - Get all records
 * - Model::where('status', 'active')->all() - Query with conditions
 * - Model::find(1) - Find by ID
 * - Model::create(['name' => 'John']) - Create new record
 * - \$model->save() - Update record
 * - \$model->delete() - Delete record
 * 
 * See ACTIVERECORD_USAGE.md for more examples
 */
class Model extends BaseModel
{
    protected string \$table = '{$tableName}';
    
    // Model fields - add your table fields here for IDE autocomplete
    // Example:
    // public \$id;
    // public \$name;
    // public \$email;
    // public \$status;
    // public \$created_at;
    // public \$updated_at;
    
    /**
     * Optional: Define relationships
     * 
     * Example:
     * public function posts()
     * {
     *     return \$this->hasMany(PostsModel::class, 'user_id');
     * }
     */
}
PHP;

file_put_contents($modelPath, $modelContent);

echo "\n";
echo "═══════════════════════════════════════════════════════════\n";
echo "✅ MODEL GENERATED SUCCESSFULLY!\n";
echo "═══════════════════════════════════════════════════════════\n";
echo "📦 Module: {$moduleName}\n";
echo "📄 File: Model.php\n";
echo "📁 Location: {$modelPath}\n";
echo "📊 Table Name: {$tableName}\n";
echo "\n💡 Tip: Use 'apimake usetable <table_name>' to auto-generate fields from database!\n";
