<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230817131031 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" ALTER password DROP DEFAULT');
        $this->addSql('ALTER TABLE "user" ALTER status DROP DEFAULT');
        $this->addSql('ALTER TABLE user_profile ALTER age DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user_profile" ALTER age SET DEFAULT 18');
        $this->addSql('ALTER TABLE "user" ALTER password SET DEFAULT \'\'');
        $this->addSql('ALTER TABLE "user" ALTER status SET DEFAULT \'\'');
    }
}
