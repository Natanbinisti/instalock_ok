<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240417165638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE image_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE post_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE reply_comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE comment (id INT NOT NULL, post_id INT NOT NULL, author_id INT NOT NULL, content TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9474526C4B89032C ON comment (post_id)');
        $this->addSql('CREATE INDEX IDX_9474526CF675F31B ON comment (author_id)');
        $this->addSql('CREATE TABLE image (id INT NOT NULL, post_id INT DEFAULT NULL, comment_id INT DEFAULT NULL, avatar_id INT DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C53D045F4B89032C ON image (post_id)');
        $this->addSql('CREATE INDEX IDX_C53D045FF8697D13 ON image (comment_id)');
        $this->addSql('CREATE INDEX IDX_C53D045F86383B10 ON image (avatar_id)');
        $this->addSql('COMMENT ON COLUMN image.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE post (id INT NOT NULL, author_id INT NOT NULL, retweet_id INT DEFAULT NULL, content TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DF675F31B ON post (author_id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D72A1C5CA ON post (retweet_id)');
        $this->addSql('CREATE TABLE reply_comment (id INT NOT NULL, comment_id INT NOT NULL, post_id INT NOT NULL, content TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_89CA3BAEF8697D13 ON reply_comment (comment_id)');
        $this->addSql('CREATE INDEX IDX_89CA3BAE4B89032C ON reply_comment (post_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON "user" (username)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4B89032C FOREIGN KEY (post_id) REFERENCES post (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F4B89032C FOREIGN KEY (post_id) REFERENCES post (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FF8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F86383B10 FOREIGN KEY (avatar_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D72A1C5CA FOREIGN KEY (retweet_id) REFERENCES post (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reply_comment ADD CONSTRAINT FK_89CA3BAEF8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reply_comment ADD CONSTRAINT FK_89CA3BAE4B89032C FOREIGN KEY (post_id) REFERENCES post (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE image_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE post_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE reply_comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C4B89032C');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE image DROP CONSTRAINT FK_C53D045F4B89032C');
        $this->addSql('ALTER TABLE image DROP CONSTRAINT FK_C53D045FF8697D13');
        $this->addSql('ALTER TABLE image DROP CONSTRAINT FK_C53D045F86383B10');
        $this->addSql('ALTER TABLE post DROP CONSTRAINT FK_5A8A6C8DF675F31B');
        $this->addSql('ALTER TABLE post DROP CONSTRAINT FK_5A8A6C8D72A1C5CA');
        $this->addSql('ALTER TABLE reply_comment DROP CONSTRAINT FK_89CA3BAEF8697D13');
        $this->addSql('ALTER TABLE reply_comment DROP CONSTRAINT FK_89CA3BAE4B89032C');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE reply_comment');
        $this->addSql('DROP TABLE "user"');
    }
}
