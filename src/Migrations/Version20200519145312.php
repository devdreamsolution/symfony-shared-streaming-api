<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200519145312 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD email VARCHAR(255) NOT NULL, ADD password VARCHAR(255) NOT NULL, ADD name VARCHAR(255) NOT NULL, ADD surename VARCHAR(255) NOT NULL, ADD picture VARCHAR(255) DEFAULT NULL, ADD age SMALLINT DEFAULT NULL, ADD vat DOUBLE PRECISION DEFAULT NULL, ADD address VARCHAR(255) DEFAULT NULL, ADD city_residence VARCHAR(255) DEFAULT NULL, ADD group_age SMALLINT DEFAULT NULL, ADD gender SMALLINT DEFAULT NULL, ADD lang VARCHAR(255) NOT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP email, DROP password, DROP name, DROP surename, DROP picture, DROP age, DROP vat, DROP address, DROP city_residence, DROP group_age, DROP gender, DROP lang, DROP created_at, DROP updated_at');
    }
}
