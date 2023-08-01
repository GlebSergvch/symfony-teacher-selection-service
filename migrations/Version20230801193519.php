<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230801193519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER INDEX group_name_key RENAME TO UNIQ_6DC044C55E237E06');
        $this->addSql('ALTER INDEX skill_name_key RENAME TO UNIQ_5E3DE4775E237E06');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER INDEX uniq_6dc044c55e237e06 RENAME TO group_name_key');
        $this->addSql('ALTER INDEX uniq_5e3de4775e237e06 RENAME TO skill_name_key');
    }
}
