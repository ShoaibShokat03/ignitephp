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

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->db = $model->getDb();
        $this->table = $model->getTable();
    }

    /**
     * Add WHERE condition
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

        return $this;
    }

    /**
     * Convenience filter method (alias of where) that also supports callables
     */
    public function filter($column, $operator = null, $value = null): self
    {
        if ($column === null) {
            return $this;
        }

        if (is_callable($column) && !is_string($column)) {
            $column($this);
            return $this;
        }

        return $this->where($column, $operator, $value);
    }

    /**
     * Add LIKE-based search across multiple columns
     */
    public function search(string $term, array $columns, string $boolean = 'AND'): self
    {
        $term = trim($term);

        if ($term === '' || empty($columns)) {
            return $this;
        }

        $clauses = [];
        $escaped = '%' . $this->db->escape($term) . '%';

        foreach ($columns as $column) {
            $column = trim((string)$column);
            if ($column === '') {
                continue;
            }
            $clauses[] = "`{$column}` LIKE '{$escaped}'";
        }

        if (empty($clauses)) {
            return $this;
        }

        $logic = strtoupper($boolean) === 'OR' ? 'OR' : 'AND';

        $this->where[] = [
            'raw' => true,
            'sql' => '(' . implode(' OR ', $clauses) . ')',
            'logic' => $logic
        ];

        return $this;
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

        return $this;
    }

    /**
     * Add WHERE IN condition
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

        return $this;
    }

    /**
     * Add WHERE NOT IN condition
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

        return $this;
    }

    /**
     * Add ORDER BY clause
     */
    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orderBy[] = [
            'column' => $column,
            'direction' => strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC'
        ];

        return $this;
    }

    /**
     * Add LIMIT clause
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Add OFFSET clause
     */
    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Alias for offset to match common pagination naming
     */
    public function skip(int $offset): self
    {
        return $this->offset($offset);
    }

    /**
     * Alias for limit to match common pagination naming
     */
    public function take(int $limit): self
    {
        return $this->limit($limit);
    }

    /**
     * Set columns to select
     */
    public function select(array|string $columns): self
    {
        $this->select = is_array($columns) ? $columns : [$columns];
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
     * Build WHERE clause SQL
     */
    protected function buildWhere(): string
    {
        if (empty($this->where)) {
            return '';
        }

        $conditions = [];
        foreach ($this->where as $index => $condition) {
            if (!empty($condition['raw'])) {
                $logic = $index > 0 ? ($condition['logic'] ?? '') : '';
                $conditions[] = ($logic ? $logic . ' ' : '') . $condition['sql'];
                continue;
            }

            $column = $condition['column'];
            $operator = $condition['operator'];
            $value = $condition['value'];
            $logic = $index > 0 ? $condition['logic'] : '';

            if ($operator === 'IN' || $operator === 'NOT IN') {
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
     */
    protected function buildSql(): string
    {
        $select = $this->buildSelect();
        $where = $this->buildWhere();
        $orderBy = $this->buildOrderBy();
        $limit = $this->buildLimit();

        $sql = "SELECT {$select} FROM `{$this->table}`";
        if ($where) $sql .= ' ' . $where;
        if ($orderBy) $sql .= ' ' . $orderBy;
        if ($limit) $sql .= ' ' . $limit;

        return $sql;
    }

    /**
     * Execute query and return all results
     */
    public function all(): array
    {
        $sql = $this->buildSql();
        $result = $this->db->query($sql);

        $records = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $record = $this->model->newInstance($row);
                $records[] = $record;
            }
        }

        // Eager load relationships if specified
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

