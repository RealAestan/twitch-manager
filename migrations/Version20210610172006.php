<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210610172006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add broadcaster table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE broadcaster_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE broadcaster (id INT NOT NULL, twitch_id VARCHAR(255) NOT NULL, stream_data JSON DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, fetched_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE broadcaster_id_seq CASCADE');
        $this->addSql('DROP TABLE broadcaster');
    }
}
