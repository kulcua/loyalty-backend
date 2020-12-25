<?php

namespace OpenLoyalty\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20201224032321 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE date_maintenance ADD user_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE date_maintenance ADD CONSTRAINT FK_10FDCD35A76ED395 FOREIGN KEY (user_id) REFERENCES ol__user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_10FDCD35A76ED395 ON date_maintenance (user_id)');
        $this->addSql('ALTER TABLE ol__earning_rule ALTER pos TYPE TEXT');
        $this->addSql('ALTER TABLE ol__earning_rule ALTER pos DROP DEFAULT');
        $this->addSql('ALTER TABLE ol__earning_rule ALTER reward_campaign_id TYPE UUID');
        $this->addSql('ALTER TABLE ol__earning_rule ALTER reward_campaign_id DROP DEFAULT');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE ol__earning_rule ALTER pos TYPE TEXT');
        $this->addSql('ALTER TABLE ol__earning_rule ALTER pos DROP DEFAULT');
        $this->addSql('ALTER TABLE ol__earning_rule ALTER reward_campaign_id TYPE UUID');
        $this->addSql('ALTER TABLE ol__earning_rule ALTER reward_campaign_id DROP DEFAULT');
        $this->addSql('ALTER TABLE date_maintenance DROP CONSTRAINT FK_10FDCD35A76ED395');
        $this->addSql('DROP INDEX IDX_10FDCD35A76ED395');
        $this->addSql('ALTER TABLE date_maintenance DROP user_id');
    }
}
