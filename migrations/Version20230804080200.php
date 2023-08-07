<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230804080200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $logins = $this->connection->fetchAllKeyValue('SELECT id, login FROM "user" ORDER BY id');

        $distinct = array_unique($logins);
        $duplicates = array_diff_key($logins, $distinct);

        foreach ($duplicates as $id => $login) {
            $newUserLogin = $this->createUserLogin();
            $this->addSql("UPDATE \"user\" SET login = '$newUserLogin' WHERE id = '$id'");
        }

        $this->addSql('CREATE UNIQUE INDEX user__login__uniq__idx ON "user" (login)');
    }

    private function createUserLogin() {
        $newUserLogin = 'user' . rand(1, 10000);
        $userLoginExists = $this->connection->fetchOne("SELECT EXISTS (SELECT * FROM \"user\" WHERE login = '$newUserLogin')");
        if ($userLoginExists) {
            $this->createUserLogin();
        }

        return $newUserLogin;
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX user__login__uniq__idx');
    }
}
