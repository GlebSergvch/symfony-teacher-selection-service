<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20230613150927 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE "user" (id BIGSERIAL NOT NULL, login VARCHAR(32) NOT NULL, role VARCHAR(32) NOT NULL, user_profile_id BIGINT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user_profile" (id BIGSERIAL NOT NULL, firstname VARCHAR(128), middlename VARCHAR(128), lastname VARCHAR(128), gender VARCHAR(32), created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_by BIGINT DEFAULT NULL, updated_by BIGINT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "skill" (id BIGSERIAL NOT NULL, name VARCHAR(128) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_by BIGINT DEFAULT NULL, updated_by BIGINT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "group" (id BIGSERIAL NOT NULL, name VARCHAR(128) NOT NULL, minimum_size INT NOT NULL, maximum_size INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_by BIGINT DEFAULT NULL, updated_by BIGINT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "student_group" (id BIGSERIAL NOT NULL, student_id BIGINT NOT NULL, group_id BIGINT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_by BIGINT DEFAULT NULL, updated_by BIGINT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "teacher_skill" (id BIGSERIAL NOT NULL, teacher_id BIGINT NOT NULL, skill_id BIGINT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_by BIGINT DEFAULT NULL, updated_by BIGINT DEFAULT NULL, PRIMARY KEY(id))');

        // add foreign keys and indexes to user table

        $this->addSql('CREATE UNIQUE INDEX user__user_profile_id__uniq__idx ON "user" (user_profile_id)');

        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT user__user_profile_id__fk FOREIGN KEY (user_profile_id) REFERENCES "user_profile" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        // add foreign keys and indexes to user_profile table

        $this->addSql('CREATE INDEX user_profile__created_by__idx ON "user_profile" (created_by)');
        $this->addSql('CREATE INDEX user_profile__updated_by__idx ON "user_profile" (updated_by)');

        $this->addSql('ALTER TABLE "user_profile" ADD CONSTRAINT user_profile__created_by__fk FOREIGN KEY (created_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user_profile" ADD CONSTRAINT user_profile__updated_by__fk FOREIGN KEY (updated_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        // add foreign keys and indexes to skill table

        $this->addSql('CREATE UNIQUE INDEX skill__name__uniq__idx ON "skill" (name)');
        $this->addSql('CREATE INDEX skill__created_by__idx ON "skill" (created_by)');
        $this->addSql('CREATE INDEX skill__updated_by__idx ON "skill" (updated_by)');

        $this->addSql('ALTER TABLE "skill" ADD CONSTRAINT skill__created_by__fk FOREIGN KEY (created_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "skill" ADD CONSTRAINT skill__updated_by__fk FOREIGN KEY (updated_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        // add foreign keys and indexes to group table

        $this->addSql('CREATE UNIQUE INDEX group__name__uniq__idx ON "group" (name)');
        $this->addSql('CREATE INDEX group__created_by__idx ON "group" (created_by)');
        $this->addSql('CREATE INDEX group__updated_by__idx ON "group" (updated_by)');

        $this->addSql('ALTER TABLE "group" ADD CONSTRAINT group__created_by__fk FOREIGN KEY (created_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "group" ADD CONSTRAINT group__updated_by__fk FOREIGN KEY (updated_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        // add foreign keys and indexes to student_group table

        $this->addSql('CREATE INDEX student_group__student_id__idx ON "student_group" (student_id)');
        $this->addSql('CREATE INDEX student_group__group_id__idx ON "student_group" (group_id)');
        $this->addSql('CREATE INDEX student_group__created_by__idx ON "student_group" (created_by)');
        $this->addSql('CREATE INDEX student_group__updated_by__idx ON "student_group" (updated_by)');
        $this->addSql('CREATE UNIQUE INDEX student_group__student_id__group_id__uniq ON "student_group" (student_id, group_id)');

        $this->addSql('ALTER TABLE "student_group" ADD CONSTRAINT student_group__student_id__fk FOREIGN KEY (student_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "student_group" ADD CONSTRAINT student_group__group_id__fk FOREIGN KEY (group_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "student_group" ADD CONSTRAINT student_group__created_by__fk FOREIGN KEY (created_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "student_group" ADD CONSTRAINT student_group__updated_by__fk FOREIGN KEY (updated_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        // add foreign keys and indexes to teacher_skill table

        $this->addSql('CREATE INDEX teacher_skill__teacher_id__idx ON "teacher_skill" (teacher_id)');
        $this->addSql('CREATE INDEX teacher_skill__skill_id__idx ON "teacher_skill" (skill_id)');
        $this->addSql('CREATE INDEX teacher_skill__created_by__idx ON "teacher_skill" (created_by)');
        $this->addSql('CREATE INDEX teacher_skill__updated_by__idx ON "teacher_skill" (updated_by)');
        $this->addSql('CREATE UNIQUE INDEX teacher_skill__teacher_id__skill_id__uniq ON teacher_skill (teacher_id, skill_id)');

        $this->addSql('ALTER TABLE "teacher_skill" ADD CONSTRAINT teacher_skill__teacher_id__fk FOREIGN KEY (teacher_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "teacher_skill" ADD CONSTRAINT teacher_skill__skill_id__fk FOREIGN KEY (skill_id) REFERENCES "skill" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "teacher_skill" ADD CONSTRAINT teacher_skill__created_by__fk FOREIGN KEY (created_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "teacher_skill" ADD CONSTRAINT teacher_skill__updated_by__fk FOREIGN KEY (updated_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {

        // drop foreign keys and indexes to teacher_skill table

        $this->addSql('ALTER TABLE "teacher_skill" DROP CONSTRAINT teacher_skill__updated_by__fk');
        $this->addSql('ALTER TABLE "teacher_skill" DROP CONSTRAINT teacher_skill__created_by__fk');
        $this->addSql('ALTER TABLE "teacher_skill" DROP CONSTRAINT teacher_skill__skill_id__fk');
        $this->addSql('ALTER TABLE "teacher_skill" DROP CONSTRAINT teacher_skill__teacher_id__fk');

        $this->addSql('DROP INDEX teacher_skill__teacher_id__skill_id__uniq');
        $this->addSql('DROP INDEX teacher_skill__updated_by__idx');
        $this->addSql('DROP INDEX teacher_skill__created_by__idx');
        $this->addSql('DROP INDEX teacher_skill__skill_id__idx');
        $this->addSql('DROP INDEX teacher_skill__teacher_id__idx');


        // drop foreign keys and indexes to student_group table

        $this->addSql('ALTER TABLE "student_group" DROP CONSTRAINT student_group__updated_by__fk');
        $this->addSql('ALTER TABLE "student_group" DROP CONSTRAINT student_group__created_by__fk');
        $this->addSql('ALTER TABLE "student_group" DROP CONSTRAINT student_group__group_id__fk');
        $this->addSql('ALTER TABLE "student_group" DROP CONSTRAINT student_group__student_id__fk');

        $this->addSql('DROP INDEX student_group__student_id__group_id__uniq');
        $this->addSql('DROP INDEX student_group__updated_by__idx');
        $this->addSql('DROP INDEX student_group__created_by__idx');
        $this->addSql('DROP INDEX student_group__group_id__idx');
        $this->addSql('DROP INDEX student_group__student_id__idx');

        // drop foreign keys and indexes to group table

        $this->addSql('ALTER TABLE "group" DROP CONSTRAINT group__updated_by__fk');
        $this->addSql('ALTER TABLE "group" DROP CONSTRAINT group__created_by__fk');

        $this->addSql('DROP INDEX group__updated_by__idx');
        $this->addSql('DROP INDEX group__created_by__idx');
        $this->addSql('DROP INDEX group__name__uniq__idx');

        // drop foreign keys and indexes to skill table

        $this->addSql('ALTER TABLE "skill" DROP CONSTRAINT skill__updated_by__fk');
        $this->addSql('ALTER TABLE "skill" DROP CONSTRAINT skill__created_by__fk');

        $this->addSql('DROP INDEX skill__updated_by__idx');
        $this->addSql('DROP INDEX skill__created_by__idx');
        $this->addSql('DROP INDEX skill__name__uniq__idx');

        // drop foreign keys and indexes to user_profile table

        $this->addSql('ALTER TABLE "user_profile" DROP CONSTRAINT user_profile__updated_by__fk');
        $this->addSql('ALTER TABLE "user_profile" DROP CONSTRAINT user_profile__created_by__fk');

        $this->addSql('DROP INDEX user_profile__updated_by__idx');
        $this->addSql('DROP INDEX user_profile__created_by__idx');


        // drop foreign keys and indexes to user table

        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT user__user_profile_id__fk');

        $this->addSql('DROP INDEX user__user_profile_id__uniq__idx');

        // drop tables

        $this->addSql('DROP TABLE "teacher_skill"');
        $this->addSql('DROP TABLE "student_group"');
        $this->addSql('DROP TABLE "group"');
        $this->addSql('DROP TABLE "skill"');
        $this->addSql('DROP TABLE "user_profile"');
        $this->addSql('DROP TABLE "user"');
    }
}