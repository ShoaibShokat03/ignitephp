<?php

namespace App\Core;

use App\Database\Db;

/**
 * Query Builder for ActiveRecord-like queries
 * Supports fluent interface: Model::where()->andWhere()->orderBy()->all()
 */
class QueryBuilder
{
    protected Model $model;
    protected Db $db;
    protected string $table;
    protected array $where = [];
    protected array $whereParams = [];
    protected array $orderBy = [];
    protected ?int $limit = null;
    protected ?int $offset = null;
    protected array $select = ['*'];
    protected array $with = []; // For eager loading relationships
    protected ?string $cachedSql = null; // Cache built SQL until query changes
    protected bool $sqlDirty = true; // Flag to rebuild SQL

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->db = $model->getDb();
        $this->table = $model->getTable();
    }

    /**
     * Add WHERE condition
     * Optimized: Marks SQL as dirty when query changes
     */
    public function where(string $column, $operator = null, $value = null): self
    {
        // Support: where('id', 1) or where('id', '=', 1) or where(['id' => 1])
        if (is_array($column)) {
            foreach ($column as $key => $val) {
                $this->where($key, '=', $val);
            }
            return $this;
        }

        if ($value === null && $operator !== null) {
            // where('id', 1) -> where id = 1
            $value = $operator;
            $operator = '=';
        }

        $this->where[] = [
            'column' => $column,
            'operator' => $operator ?? '=',
            'value' => $value,
            'logic' => 'AND'
        ];
        
        $this->sqlDirty = true; // Mark SQL as needing rebuild

        return $this;
    }
    
    /**
     * Filter method - supports multiple formats for better readability
     * Usage: 
     *   ->filter('name', 'John')
     *   ->filter('age', '>', 20)
     *   ->filter(['name' => 'John', 'status' => 'active'])
     *   ->filter('age', '>20')  // Operator and value combined
     */
    public function filter($column, $operator = null, $value = null): self
    {
        // Support array format: filter(['name' => 'John', 'age' => '>20'])
        if (is_array($column)) {
            foreach ($column as $key => $val) {
                // Check if value contains operator (e.g., '>20', '<=100')
                if (is_string($val) && preg_match('/^(>=|<=|>|<|!=|<>)\s*(.+)$/', $val, $matches)) {
                    $this->filter($key, $matches[1], $matches[2]);
                } else {
                    $this->filter($key, '=', $val);
                }
            }
            return $this;
        }
        
        // Support combined operator+value: filter('age', '>20')
        if ($value === null && $operator !== null && is_string($operator) && preg_match('/^(>=|<=|>|<|!=|<>)\s*(.+)$/', $operator, $matches)) {
            return $this->where($column, $matches[1], $matches[2]);
        }
        
        // Standard format: filter('name', 'John') or filter('age', '>', 20)
        return $this->where($column, $operator, $value);
    }

    /**
     * Add AND WHERE condition
     */
    public function andWhere(string $column, $operator = null, $value = null): self
    {
        return $this->where($column, $operator, $value);
    }

    /**
     * Add OR WHERE condition
     * Optimized: Marks SQL as dirty when query changes
     */
    public function orWhere(string $column, $operator = null, $value = null): self
    {
        if (is_array($column)) {
            foreach ($column as $key => $val) {
                $this->orWhere($key, '=', $val);
            }
            return $this;
        }

        if ($value === null && $operator !== null) {
            $value = $operator;
            $operator = '=';
        }

        $this->where[] = [
            'column' => $column,
            'operator' => $operator ?? '=',
            'value' => $value,
            'logic' => 'OR'
        ];
        
        $this->sqlDirty = true; // Mark SQL as needing rebuild

        return $this;
    }

    /**
     * Add WHERE IN condition
     * Optimized: Marks SQL as dirty when query changes
     */
    public function whereIn(string $column, array $values): self
    {
        if (empty($values)) {
            // Empty IN clause - return no results
            $this->where[] = [
                'column' => '1',
                'operator' => '=',
                'value' => '0',
                'logic' => 'AND'
            ];
            $this->sqlDirty = true;
            return $this;
        }

        $escaped = array_map(function($val) {
            return is_numeric($val) ? (int)$val : "'" . $this->db->escape($val) . "'";
        }, $values);

        $this->where[] = [
            'column' => $column,
            'operator' => 'IN',
            'value' => '(' . implode(', ', $escaped) . ')',
            'logic' => 'AND'
        ];
        
        $this->sqlDirty = true;

        return $this;
    }

    /**
     * Add WHERE NOT IN condition
     * Optimized: Marks SQL as dirty when query changes
     */
    public function whereNotIn(string $column, array $values): self
    {
        $escaped = array_map(function($val) {
            return is_numeric($val) ? (int)$val : "'" . $this->db->escape($val) . "'";
        }, $values);

        $this->where[] = [
            'column' => $column,
            'operator' => 'NOT IN',
            'value' => '(' . implode(', ', $escaped) . ')',
            'logic' => 'AND'
        ];
        
        $this->sqlDirty = true;

        return $this;
    }

    /**
     * Add ORDER BY clause
     * Optimized: Marks SQL as dirty when query changes
     */
    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orderBy[] = [
            'column' => $column,
            'direction' => strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC'
        ];
        
        $this->sqlDirty = true;

        return $this;
    }

    /**
     * Add LIMIT clause
     * Optimized: Marks SQL as dirty when query changes
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;
        $this->sqlDirty = true;
        return $this;
    }

    /**
     * Add OFFSET clause
     * Optimized: Marks SQL as dirty when query changes
     */
    public function offset(int $offset): self
    {
        $this->offset = $offset;
        $this->sqlDirty = true;
        return $this;
    }

    /**
     * Set columns to select
     * Optimized: Marks SQL as dirty when query changes
     */
    public function select(array|string $columns): self
    {
        $this->select = is_array($columns) ? $columns : [$columns];
        $this->sqlDirty = true;
        return $this;
    }

    /**
     * Eager load relationships
     */
    public function with(array|string $relations): self
    {
        $this->with = is_array($relations) ? $relations : [$relations];
        return $this;
    }
    
    /**
     * Search across multiple columns
     * Usage: 
     *   ->search('john', ['name', 'email'])
     *   ->search('john', 'name')  // Single column
     * 
     * Creates: WHERE (name LIKE '%john%' OR email LIKE '%john%')
     */
    public function search(string $term, array|string $columns): self
    {
        if (empty($term)) {
            return $this;
        }
        
        // Convert single column to array
        $searchColumns = is_array($columns) ? $columns : [$columns];
        
        if (empty($searchColumns)) {
            return $this;
        }
        
        $db = $this->db;
        $escapedTerm = $db->escape($term);
        $searchTerm = "%{$escapedTerm}%";
        
        // Build OR conditions for each column
        $conditions = [];
        foreach ($searchColumns as $column) {
            $conditions[] = "`{$column}` LIKE '{$searchTerm}'";
        }
        
        // Add as a grouped OR condition
        $this->where[] = [
            'column' => '(' . implode(' OR ', $conditions) . ')',
            'operator' => '',
            'value' => '',
            'logic' => 'AND'
        ];
        
        $this->sqlDirty = true;
        
        return $this;
    }
    
    /**
     * Skip records (alias for offset) - better for pagination readability
     * Usage: ->skip(10)  // Skip first 10 records
     */
    public function skip(int $count): self
    {
        return $this->offset($count);
    }
    
    /**
     * Get records from a range (for pagination)
     * Usage: 
     *   ->fromTo(0, 10)   // Get records 0-10 (first page, 10 items)
     *   ->fromTo(10, 20)  // Get records 10-20 (second page, 10 items)
     * 
     * This sets offset and limit automatically
     */
    public function fromTo(int $from, int $to): self
    {
        $offset = $from;
        $limit = $to - $from;
        
        if ($limit < 0) {
            $limit = 0;
        }
        
        $this->offset($offset);
        $this->limit($limit);
        
        return $this;
    }

    /**
     * Build WHERE clause SQL
     * Optimized: Handles grouped conditions from search() method
     */
    protected function buildWhere(): string
    {
        if (empty($this->where)) {
            return '';
        }

        $conditions = [];
        foreach ($this->where as $index => $condition) {
            $column = $condition['column'];
            $operator = $condition['operator'];
            $value = $condition['value'];
            $logic = $index > 0 ? $condition['logic'] : '';

            // Handle grouped conditions (from search method)
            if (empty($operator) && empty($value) && strpos($column, '(') === 0) {
                // This is a grouped condition like "(name LIKE '%term%' OR email LIKE '%term%')"
                $conditions[] = ($logic ? $logic . ' ' : '') . $column;
            } elseif ($operator === 'IN' || $operator === 'NOT IN') {
                $conditions[] = ($logic ? $logic . ' ' : '') . "`{$column}` {$operator} {$value}";
            } else {
                $escapedValue = is_numeric($value) ? (int)$value : "'" . $this->db->escape($value) . "'";
                $conditions[] = ($logic ? $logic . ' ' : '') . "`{$column}` {$operator} {$escapedValue}";
            }
        }

        return 'WHERE ' . implode(' ', $conditions);
    }

    /**
     * Build ORDER BY clause SQL
     */
    protected function buildOrderBy(): string
    {
        if (empty($this->orderBy)) {
            return '';
        }

        $orders = [];
        foreach ($this->orderBy as $order) {
            $orders[] = "`{$order['column']}` {$order['direction']}";
        }

        return 'ORDER BY ' . implode(', ', $orders);
    }

    /**
     * Build LIMIT clause SQL
     */
    protected function buildLimit(): string
    {
        if ($this->limit === null) {
            return '';
        }

        $sql = "LIMIT {$this->limit}";
        if ($this->offset !== null) {
            $sql = "LIMIT {$this->offset}, {$this->limit}";
        }

        return $sql;
    }

    /**
     * Build SELECT clause SQL
     */
    protected function buildSelect(): string
    {
        if (in_array('*', $this->select)) {
            return '*';
        }

        return implode(', ', array_map(function($col) {
            return "`{$col}`";
        }, $this->select));
    }

    /**
     * Build complete SQL query
     * Optimized: Caches SQL until query changes
     */
    protected function buildSql(): string
    {
        // Return cached SQL if query hasn't changed
        if (!$this->sqlDirty && $this->cachedSql !== null) {
            return $this->cachedSql;
        }
        
        $select = $this->buildSelect();
        $where = $this->buildWhere();
        $orderBy = $this->buildOrderBy();
        $limit = $this->buildLimit();

        $sql = "SELECT {$select} FROM `{$this->table}`";
        if ($where) $sql .= ' ' . $where;
        if ($orderBy) $sql .= ' ' . $orderBy;
        if ($limit) $sql .= ' ' . $limit;

        // Cache the SQL
        $this->cachedSql = $sql;
        $this->sqlDirty = false;
        
        return $sql;
    }

    /**
     * Execute query and return all results
     * Optimized: Batch relationship loading, optimized object creation
     */
    public function all(): array
    {
        $sql = $this->buildSql();
        $result = $this->db->query($sql);

        $records = [];
        if ($result) {
            // Optimize: Pre-fetch all rows into array first
            $rows = [];
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            
            // Batch create instances
            foreach ($rows as $row) {
                $record = $this->model->newInstance($row);
                $records[] = $record;
            }
        }

        // Eager load relationships if specified (optimized batch loading)
        if (!empty($this->with) && !empty($records)) {
            $this->loadRelations($records);
        }

        return $records;
    }

    /**
     * Execute query and return first result
     */
    public function one(): ?Model
    {
        $this->limit(1);
        $results = $this->all();
        return !empty($results) ? $results[0] : null;
    }

    /**
     * Execute query and return count
     */
    public function count(): int
    {
        $sql = "SELECT COUNT(*) as count FROM `{$this->table}`";
        $where = $this->buildWhere();
        if ($where) $sql .= ' ' . $where;

        $result = $this->db->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            return (int)$row['count'];
        }

        return 0;
    }

    /**
     * Check if record exists
     */
    public function exists(): bool
    {
        return $this->count() > 0;
    }

    /**
     * Eager load relationships for records
     */
    protected function loadRelations(array $records): void
    {
        foreach ($this->with as $relationName) {
            if (method_exists($this->model, $relationName)) {
                $relation = $this->model->$relationName();
                
                if (is_array($relation) && isset($relation['type'], $relation['class'], $relation['foreignKey'])) {
                    $this->loadRelation($records, $relationName, $relation);
                }
            }
        }
    }

    /**
     * Load a specific relationship
     */
    protected function loadRelation(array $records, string $relationName, array $relation): void
    {
        $type = $relation['type'];
        $relatedClass = $relation['class'];
        $foreignKey = $relation['foreignKey'];
        $localKey = $relation['localKey'] ?? 'id';

        // Get all foreign keys from records
        $foreignKeys = array_filter(array_map(function($record) use ($localKey) {
            return $record->getAttribute($localKey);
        }, $records));

        if (empty($foreignKeys)) {
            return;
        }

        // Load related records
        $relatedRecords = [];
        if ($type === 'hasMany' || $type === 'hasOne') {
            // For hasMany/hasOne: related table has foreignKey pointing to parent's localKey
            $relatedRecords = $relatedClass::whereIn($foreignKey, array_unique($foreignKeys))->all();
        } elseif ($type === 'belongsTo') {
            // For belongsTo: parent has foreignKey pointing to related's localKey
            $relatedLocalKey = $relation['localKey'] ?? 'id';
            $relatedRecords = $relatedClass::whereIn($relatedLocalKey, array_unique($foreignKeys))->all();
        }

        // Map related records to parent records
        $relatedMap = [];
        foreach ($relatedRecords as $related) {
            if ($type === 'belongsTo') {
                // For belongsTo: match on related's localKey
                $relatedLocalKey = $relation['localKey'] ?? 'id';
                $key = $related->getAttribute($relatedLocalKey);
            } else {
                // For hasMany/hasOne: match on related's foreignKey
                $key = $related->getAttribute($foreignKey);
            }
            
            if ($type === 'hasMany') {
                if (!isset($relatedMap[$key])) {
                    $relatedMap[$key] = [];
                }
                $relatedMap[$key][] = $related;
            } else {
                $relatedMap[$key] = $related;
            }
        }

        // Attach related records to parent records
        foreach ($records as $record) {
            $key = $record->getAttribute($localKey);
            if (isset($relatedMap[$key])) {
                $record->setRelation($relationName, $relatedMap[$key]);
            } else {
                $record->setRelation($relationName, $type === 'hasMany' ? [] : null);
            }
        }
    }
}

