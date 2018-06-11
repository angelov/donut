<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170503123908 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE community DROP FOREIGN KEY FK_1B604033F675F31B');
        $this->addSql('ALTER TABLE community_member DROP FOREIGN KEY FK_12E0F8BA76ED395');
        $this->addSql('ALTER TABLE thought DROP FOREIGN KEY FK_91BB9F6CF675F31B');
        $this->addSql('ALTER TABLE friendship_request DROP FOREIGN KEY FK_6CC48EE12130303A');
        $this->addSql('ALTER TABLE friendship_request DROP FOREIGN KEY FK_6CC48EE129F6EE60');
        $this->addSql('ALTER TABLE friendship DROP FOREIGN KEY FK_7234A45FA76ED395');
        $this->addSql('ALTER TABLE friendship DROP FOREIGN KEY FK_7234A45F6A5458E8');

        $this->addSql('ALTER TABLE community CHANGE author_id author_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE community_member CHANGE user_id user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE user CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE thought CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE author_id author_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE friendship_request CHANGE from_user_id from_user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE to_user_id to_user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE friendship CHANGE friend_id friend_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE user_id user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');

        $this->addSql('ALTER TABLE community ADD CONSTRAINT FK_1B604033F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE community_member ADD CONSTRAINT FK_12E0F8BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE thought ADD CONSTRAINT FK_91BB9F6CF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE friendship_request ADD CONSTRAINT FK_6CC48EE12130303A FOREIGN KEY (from_user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE friendship_request ADD CONSTRAINT FK_6CC48EE129F6EE60 FOREIGN KEY (to_user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE friendship ADD CONSTRAINT FK_7234A45FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE friendship ADD CONSTRAINT FK_7234A45F6A5458E8 FOREIGN KEY (friend_id) REFERENCES user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE community DROP FOREIGN KEY FK_1B604033F675F31B');
        $this->addSql('ALTER TABLE community_member DROP FOREIGN KEY FK_12E0F8BA76ED395');
        $this->addSql('ALTER TABLE thought DROP FOREIGN KEY FK_91BB9F6CF675F31B');
        $this->addSql('ALTER TABLE friendship_request DROP FOREIGN KEY FK_6CC48EE12130303A');
        $this->addSql('ALTER TABLE friendship_request DROP FOREIGN KEY FK_6CC48EE129F6EE60');
        $this->addSql('ALTER TABLE friendship DROP FOREIGN KEY FK_7234A45FA76ED395');
        $this->addSql('ALTER TABLE friendship DROP FOREIGN KEY FK_7234A45F6A5458E8');

        $this->addSql('ALTER TABLE community CHANGE author_id author_id INT NOT NULL');
        $this->addSql('ALTER TABLE community_member CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE friendship CHANGE user_id user_id INT NOT NULL, CHANGE friend_id friend_id INT NOT NULL');
        $this->addSql('ALTER TABLE friendship_request CHANGE from_user_id from_user_id INT NOT NULL, CHANGE to_user_id to_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE thought CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE author_id author_id INT NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE id id INT AUTO_INCREMENT NOT NULL');

        $this->addSql('ALTER TABLE community ADD CONSTRAINT FK_1B604033F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE community_member ADD CONSTRAINT FK_12E0F8BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE thought ADD CONSTRAINT FK_91BB9F6CF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE friendship_request ADD CONSTRAINT FK_6CC48EE12130303A FOREIGN KEY (from_user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE friendship_request ADD CONSTRAINT FK_6CC48EE129F6EE60 FOREIGN KEY (to_user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE friendship ADD CONSTRAINT FK_7234A45FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE friendship ADD CONSTRAINT FK_7234A45F6A5458E8 FOREIGN KEY (friend_id) REFERENCES user (id)');
    }
}
