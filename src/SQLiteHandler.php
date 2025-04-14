<?php

namespace Monolite;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use PDO;

class SQLiteHandler extends AbstractProcessingHandler
{
    private PDO $pdo;

    public function __construct(
        private string $filePath,
        private string $tableName = 'logs',
        int|string|Level $level = Level::Debug,
        bool $bubble = true
    ) {
        $this->pdo = new PDO("sqlite:{$this->filePath}");
        parent::__construct($level, $bubble);
    }

    protected function write(LogRecord $record): void
    {
        /** @noinspection SqlNoDataSourceInspection */
        $sql = "INSERT INTO {$this->tableName} (channel, level, level_name, message, context, extra, created_at) VALUES (?,?,?,?,?,?,?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $record->channel,
            $record->level->value,
            $record->level->getName(),
            $record->message,
            empty($record->context) ? null : json_encode($record->context),
            empty($record->extra) ? null : json_encode($record->extra),
            $record->datetime->format('Y-m-d H:i:s'),
        ]);
    }

    public function up(): void
    {
        /** @noinspection SqlNoDataSourceInspection */
        $query = $this->pdo->prepare("CREATE TABLE IF NOT EXISTS {$this->tableName} (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            channel TEXT,
            level INTEGER,
            level_name TEXT,
            message TEXT,
            context TEXT,
            extra TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );");

        $query->execute();

    }

    public function down(): void
    {
        /** @noinspection SqlNoDataSourceInspection */
        $query = $this->pdo->prepare("DROP TABLE IF EXISTS {$this->tableName}");
        $query->execute();
    }
}
