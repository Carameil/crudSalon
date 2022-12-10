<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221117180242 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE material ALTER unit TYPE VARCHAR(5)');
        $this->addSql('ALTER TABLE position ALTER salary TYPE INT');
        $this->addSql('ALTER TABLE "user" ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE "user" ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS NULL');
        $this->addSql('COMMENT ON COLUMN "user".updated_at IS NULL');
        $this->addSql('ALTER TABLE visit ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE visit ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE visit ADD deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE visit DROP date_time');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" ALTER created_at TYPE DATE');
        $this->addSql('ALTER TABLE "user" ALTER updated_at TYPE DATE');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".updated_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE material ALTER unit TYPE TEXT');
        $this->addSql('ALTER TABLE material ALTER unit TYPE TEXT');
        $this->addSql('ALTER TABLE visit ADD date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE visit DROP created_at');
        $this->addSql('ALTER TABLE visit DROP updated_at');
        $this->addSql('ALTER TABLE visit DROP deleted_at');
        $this->addSql('COMMENT ON COLUMN visit.date_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE position ALTER salary TYPE NUMERIC(7, 2)');
    }
}
