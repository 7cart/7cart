<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191114101349 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE categories ALTER title TYPE JSONB');
        $this->addSql('ALTER TABLE categories ALTER title DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN categories.title IS \'(DC2Type:cjsonb)\'');
        $this->addSql('ALTER TABLE nodes ALTER description TYPE JSONB');
        $this->addSql('ALTER TABLE nodes ALTER description DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN nodes.title IS \'(DC2Type:cjsonb)\'');
        $this->addSql('COMMENT ON COLUMN nodes.description IS \'(DC2Type:cjsonb)\'');
        $this->addSql('COMMENT ON COLUMN nodes.categories_id IS \'(DC2Type:cjsonb)\'');
        $this->addSql('COMMENT ON COLUMN nodes.attributes IS \'(DC2Type:cjsonb)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE categories ALTER title TYPE JSONB');
        $this->addSql('ALTER TABLE categories ALTER title DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN categories.title IS NULL');
        $this->addSql('ALTER TABLE nodes ALTER description TYPE JSONB');
        $this->addSql('ALTER TABLE nodes ALTER description DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN nodes.title IS \'(DC2Type:json_array)\'');
        $this->addSql('COMMENT ON COLUMN nodes.description IS NULL');
        $this->addSql('COMMENT ON COLUMN nodes.categories_id IS \'(DC2Type:json_array)\'');
        $this->addSql('COMMENT ON COLUMN nodes.attributes IS \'(DC2Type:json_array)\'');
    }
}
