<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241125044127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blog_post_user (blog_post_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_E1B8590DA77FBEAF (blog_post_id), INDEX IDX_E1B8590DA76ED395 (user_id), PRIMARY KEY(blog_post_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog_post_user ADD CONSTRAINT FK_E1B8590DA77FBEAF FOREIGN KEY (blog_post_id) REFERENCES blog_post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blog_post_user ADD CONSTRAINT FK_E1B8590DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_post_user DROP FOREIGN KEY FK_E1B8590DA77FBEAF');
        $this->addSql('ALTER TABLE blog_post_user DROP FOREIGN KEY FK_E1B8590DA76ED395');
        $this->addSql('DROP TABLE blog_post_user');
    }
}
