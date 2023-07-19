<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230718181750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX user__user_profile_id__idx');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6496B9DD454 ON "user" (user_profile_id)');
        $this->addSql('CREATE INDEX user__user_profile_id__idx ON "user" (user_profile_id)');
        $this->addSql('DROP INDEX teacher_skill__teacher_id__skill_id__uniq');
        $this->addSql('ALTER INDEX teacher_skill__teacher_id__idx RENAME TO IDX_FC2582A41807E1D');
        $this->addSql('ALTER INDEX teacher_skill__skill_id__idx RENAME TO IDX_FC2582A5585C142');
        $this->addSql('DROP INDEX student_group__student_id__group_id__uniq');
        $this->addSql('ALTER INDEX student_group__student_id__idx RENAME TO IDX_E5F73D58CB944F1A');
        $this->addSql('ALTER INDEX student_group__group_id__idx RENAME TO IDX_E5F73D58FE54D947');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_8D93D6496B9DD454');
        $this->addSql('DROP INDEX user__user_profile_id__idx');
        $this->addSql('CREATE UNIQUE INDEX user__user_profile_id__idx ON "user" (user_profile_id)');
        $this->addSql('CREATE UNIQUE INDEX student_group__student_id__group_id__uniq ON student_group (student_id, group_id)');
        $this->addSql('ALTER INDEX idx_e5f73d58fe54d947 RENAME TO student_group__group_id__idx');
        $this->addSql('ALTER INDEX idx_e5f73d58cb944f1a RENAME TO student_group__student_id__idx');
        $this->addSql('CREATE UNIQUE INDEX teacher_skill__teacher_id__skill_id__uniq ON teacher_skill (teacher_id, skill_id)');
        $this->addSql('ALTER INDEX idx_fc2582a5585c142 RENAME TO teacher_skill__skill_id__idx');
        $this->addSql('ALTER INDEX idx_fc2582a41807e1d RENAME TO teacher_skill__teacher_id__idx');
    }
}
