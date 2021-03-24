<?php

namespace app\core\db;

use PDO;
use app\core\Application;

/**
 * Class Database
 * 
 * @author Francis Osore <francisosore8@gmail.com>
 * @package app\core
 */
class Database
{
  public PDO $pdo;

  public function __construct(array $dbParams)
  {
    $dbType = $dbParams['dbType'] ?? '';
    $dbHost = $dbParams['dbHost'] ?? '';
    $dbPort = $dbParams['dbPort'] ?? '';
    $dbName = $dbParams['dbName'] ?? '';
    $dbUser = $dbParams['dbUser'] ?? '';
    $dbPass = $dbParams['dbPass'] ?? '';

    $dsn = $dbType . ':host=' . $dbHost . ';port=' . $dbPort . ';dbname=' . $dbName;

    $this->pdo = new PDO($dsn, $dbUser, $dbPass);
    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }

  public function applyMigrations()
  {
    $this->createMigrationsTable();
    $appliedMigrations = $this->getAppliedMigrations();

    $newMigrations = [];
    $files = scandir(Application::$ROOT_DIR . '/migrations');
    $toApplyMigrations = array_diff($files, $appliedMigrations);

    // echo '<pre>';
    // var_dump($toApplyMigrations);
    // echo '</pre>';
    // exit;

    foreach ($toApplyMigrations as $migration) {
      if ($migration === '.' || $migration === '..') {
        continue;
      }

      require_once Application::$ROOT_DIR . '/migrations/' . $migration;
      $className = pathinfo($migration, PATHINFO_FILENAME);
      $instance = new $className();
      $this->log("Applying migration $migration");
      $instance->up();
      $this->log("Applied migration $migration");
      $newMigration[] = $migration;

      // echo '<pre>';
      // var_dump($className);
      // echo '</pre>';
    }

    if (!empty($newMigrations)) {
      $this->saveMigrations($newMigrations);
    } else {
      $this->log("All migrations are applied");
    }
  }

  public function createMigrationsTable()
  {
    $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations(
      id INT AUTO_INCREMENT PRIMARY KEY,
      migration VARCHAR(255),
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )ENGINE=INNODB;");
  }

  public function getAppliedMigrations()
  {
    $statement = $this->pdo->prepare("SELECT migration FROM migrations");
    $statement->execute();


    return $statement->fetchAll(PDO::FETCH_COLUMN);
  }

  public function saveMigrations(array $migrations)
  {
    // echo '<pre>';
    //   var_dump($migrations);
    //   echo '</pre>';

    $str = implode(",", array_map(fn ($m) => "('$migrations')", $migrations));
    $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES
$str
");
    $statement->execute();
  }

  public function prepare($sql)
  {
    return $this->pdo->prepare($sql);
  }

  protected function log($message)
  {
    echo '[' . date('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL;
  }
}
