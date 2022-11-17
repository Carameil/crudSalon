<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221115070657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE material ALTER unit TYPE TEXT');
        $this->addSql('ALTER TABLE material ALTER unit SET DEFAULT \'шт\'');
        $this->addSql('ALTER TABLE materials_services ALTER unit TYPE TEXT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE materials_services ALTER unit TYPE TEXT');
        $this->addSql('ALTER TABLE material ALTER unit TYPE TEXT');
        $this->addSql('ALTER TABLE material ALTER unit DROP DEFAULT');
    }
}