<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221114105714 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE material ALTER unit TYPE TEXT');
        $this->addSql('ALTER TABLE materials_services ALTER unit TYPE TEXT');
        $this->addSql('ALTER TABLE "user" ALTER status TYPE VARCHAR(16)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" ALTER status TYPE TEXT');
        $this->addSql('ALTER TABLE "user" ALTER status TYPE TEXT');
        $this->addSql('ALTER TABLE materials_services ALTER unit TYPE TEXT');
        $this->addSql('ALTER TABLE material ALTER unit TYPE TEXT');
    }
}
