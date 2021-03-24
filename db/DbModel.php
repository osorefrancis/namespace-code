<?php

namespace app\core\db;

use app\core\Model;
use app\core\Application;

/**
 * Class DbModel
 * 
 * @author Francis Osore <francisosore8@gmail.com>
 * @package app\core
 */

abstract class DbModel extends Model
{
  abstract public static function tableName(): string;

  abstract public function attributes(): array;

  abstract public static function primaryKey(): string;

  abstract public function getDisplayName(): string;


  public function save()
  {
    $tableName = $this->tableName();
    $attributes = $this->attributes();
    $params = array_map(fn ($attr) => ":$attr", $attributes);
    $statement = self::prepare(
      "INSERT INTO $tableName (" . implode(',', $attributes) . ")
      VALUES(" . implode(',', $params) . ")"
    );

    foreach ($attributes as $attribute) {
      $statement->bindvalue(":$attribute", $this->{$attribute});
    }

    $statement->execute();

    return true;
  }

  public static function findOne($where)
  {
    $tableName = static::tableName();
    $attributes = array_keys($where);
    $sql = implode("AND", array_map(fn ($attr) => "$attr = :$attr", $attributes));
    $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");
    foreach ($where as $key => $item) {
      $statement->bindValue(":$key", $item);
    }

    $statement->execute();
    return $statement->fetchObject(static::class);
  }

  public static function prepare($sql)
  {
    return Application::$app->db->pdo->prepare($sql);
  }
}
