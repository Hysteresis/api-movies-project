<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230614115611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE movie_writer (movie_id INT NOT NULL, writer_id INT NOT NULL, INDEX IDX_6E6745F78F93B6FC (movie_id), INDEX IDX_6E6745F71BC7E6B6 (writer_id), PRIMARY KEY(movie_id, writer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE movie_writer ADD CONSTRAINT FK_6E6745F78F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_writer ADD CONSTRAINT FK_6E6745F71BC7E6B6 FOREIGN KEY (writer_id) REFERENCES writer (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movie_writer DROP FOREIGN KEY FK_6E6745F78F93B6FC');
        $this->addSql('ALTER TABLE movie_writer DROP FOREIGN KEY FK_6E6745F71BC7E6B6');
        $this->addSql('DROP TABLE movie_writer');
    }
}
