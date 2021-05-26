<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170502213353 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE movie_has_genre DROP FOREIGN KEY FK_EB0C2A404296D31F');
        $this->addSql('ALTER TABLE movie_has_genre DROP FOREIGN KEY FK_EB0C2A408F93B6FC');

        $this->addSql('ALTER TABLE movie CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE movie_has_genre CHANGE movie_id movie_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE genre_id genre_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE movie_genre CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');

        $this->addSql('ALTER TABLE movie_has_genre ADD CONSTRAINT FK_EB0C2A408F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE movie_has_genre ADD CONSTRAINT FK_EB0C2A404296D31F FOREIGN KEY (genre_id) REFERENCES movie_genre (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE movie_has_genre DROP FOREIGN KEY FK_EB0C2A404296D31F');
        $this->addSql('ALTER TABLE movie_has_genre DROP FOREIGN KEY FK_EB0C2A408F93B6FC');

        $this->addSql('ALTER TABLE movie CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE movie_genre CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE movie_has_genre CHANGE movie_id movie_id INT NOT NULL, CHANGE genre_id genre_id INT NOT NULL');

        $this->addSql('ALTER TABLE movie_has_genre ADD CONSTRAINT FK_EB0C2A408F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE movie_has_genre ADD CONSTRAINT FK_EB0C2A404296D31F FOREIGN KEY (genre_id) REFERENCES movie_genre (id)');
    }
}
