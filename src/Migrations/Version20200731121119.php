<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200731121119 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("CREATE TYPE sensor_status AS ENUM('OK', 'WARN', 'ALERT');");
        $this->addSql('CREATE TABLE IF NOT EXISTS "sensors"
(
    "sensor_id"    CHAR(36)          NOT NULL PRIMARY KEY,
    "status"        sensor_status   NOT NULL,
    "created_at"    timestamptz     NOT NULL DEFAULT (NOW()),
    "updated_at"    timestamptz     NOT NULL DEFAULT (NOW())
);');
        $this->addSql('CREATE TABLE IF NOT EXISTS "measurements"
(
    "measurement_id"  CHAR(36)        NOT NULL PRIMARY KEY,
    "sensor_id"       CHAR(36)        NOT NULL REFERENCES sensors("sensor_id") ON DELETE CASCADE,
    "co2"             BIGINT          NOT NULL,
    "created_at"       timestamptz     NOT NULL
);');

    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE IF EXISTS "sensors";');
        $this->addSql('DROP TABLE IF EXISTS "measurements";');
    }
}
