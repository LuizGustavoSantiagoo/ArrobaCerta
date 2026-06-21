<?php

namespace Core\Database\ActiveRecord;

use Core\Database\Database;
use PDO;

class BelongsToMany
{
    public function __construct(
        private Model  $model,
        private string $related,
        private string $pivot_table,
        private string $from_foreign_key,
        private string $to_foreign_key,
    ) {
    }

    /**
     * @return array<Model>
     */
    public function get()
    {
        $fromTable = $this->model::table();
        $toTable = $this->related::table();

        $attributes = $toTable . '.id, ';
        foreach ($this->related::columns() as $column) {
            $attributes .= $toTable . '.' . $column . ', ';
        }
        $attributes = rtrim($attributes, ', ');

        $sql = <<<SQL
            SELECT 
                {$attributes},
                {$this->pivot_table}.application_date as pivot_application_date,
                {$this->pivot_table}.id as pivot_id
            FROM 
                {$fromTable}, {$toTable}, {$this->pivot_table}
            WHERE 
                {$toTable}.id = {$this->pivot_table}.{$this->to_foreign_key} AND
                {$fromTable}.id = {$this->pivot_table}.{$this->from_foreign_key} AND
                {$fromTable}.id = :id
        SQL;

        $pdo = Database::getDatabaseConn();
        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':id', $this->model->id);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $models = [];
        foreach ($rows as $row) {
            $obj = new $this->related($row);
            
            $obj->pivot_application_date = $row['pivot_application_date'] ?? null;
            $obj->pivot_id = $row['pivot_id'] ?? null;
            
            $models[] = $obj;
        }

        return $models;
    }

    public function count(): int
    {
        $fromTable = $this->model::table();
        $toTable = $this->related::table();

        $sql = <<<SQL
        SELECT 
            count({$toTable}.id) as total
        FROM 
            {$fromTable}, {$toTable}, {$this->pivot_table}
        WHERE 
            {$toTable}.id = {$this->pivot_table}.{$this->to_foreign_key} AND
            {$fromTable}.id = {$this->pivot_table}.{$this->from_foreign_key} AND
            {$fromTable}.id = :id
        SQL;

        $pdo = Database::getDatabaseConn();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $this->model->id);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $rows[0]['total'];
    }

    /**
     * * @param int $relatedId
     * @param array<string, mixed> $extraData
     * @return bool
     */
    public function attach(int $relatedId, array $extraData = []): bool
    {
        $pdo = Database::getDatabaseConn();

        $columns = [$this->from_foreign_key, $this->to_foreign_key];
        $values = [':from_id', ':to_id'];
        $params = [
            'from_id' => $this->model->id,
            'to_id' => $relatedId
        ];

        foreach ($extraData as $column => $value) {
            $columns[] = $column;
            $values[] = ":{$column}";
            $params[$column] = $value;
        }

        $colsStr = implode(', ', $columns);
        $valsStr = implode(', ', $values);

        $sql = "INSERT INTO {$this->pivot_table} ({$colsStr}) VALUES ({$valsStr})";

        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function detach(int $relatedId): bool
    {
        $pdo = Database::getDatabaseConn();
        $sql = "DELETE FROM {$this->pivot_table} 
                WHERE {$this->from_foreign_key} = :from_id AND {$this->to_foreign_key} = :to_id";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            'from_id' => $this->model->id,
            'to_id' => $relatedId
        ]);
    }
}