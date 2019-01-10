<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190109211642 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('SELECT setval(\'"categories_id_seq"\', (SELECT MAX(id) FROM categories))');
        $this->addSql('ALTER TABLE categories ALTER id SET DEFAULT nextval(\'categories_id_seq\')');
        $this->addSql('ALTER TABLE categories ALTER parent_id DROP NOT NULL');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF34668727ACA70 FOREIGN KEY (parent_id) REFERENCES categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3AF34668727ACA70 ON categories (parent_id)');
        $this->addSql('CREATE SEQUENCE attribute_values_id_seq');
        $this->addSql('SELECT setval(\'"attribute_values_id_seq"\', (SELECT MAX(id) FROM attribute_values))');
        $this->addSql('ALTER TABLE attribute_values ALTER id SET DEFAULT nextval(\'attribute_values_id_seq\')');
        $this->addSql('CREATE SEQUENCE attributes_id_seq');
        $this->addSql('SELECT setval(\'"attributes_id_seq"\', (SELECT MAX(id) FROM attributes))');
        $this->addSql('ALTER TABLE attributes ALTER id SET DEFAULT nextval(\'attributes_id_seq\')');
        $this->addSql('SELECT setval(\'"nodes_id_seq"\', (SELECT MAX(id) FROM nodes))');
        $this->addSql('ALTER TABLE nodes ALTER id SET DEFAULT nextval(\'nodes_id_seq\')');
        $this->addSql('ALTER TABLE nodes ALTER title DROP NOT NULL');
        $this->addSql('ALTER TABLE nodes ALTER description DROP NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE categories DROP CONSTRAINT FK_3AF34668727ACA70');
        $this->addSql('DROP INDEX IDX_3AF34668727ACA70');
        $this->addSql('ALTER TABLE categories ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE categories ALTER parent_id SET NOT NULL');
        $this->addSql('ALTER TABLE attributes ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE attribute_values ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE nodes ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE nodes ALTER title SET NOT NULL');
        $this->addSql('ALTER TABLE nodes ALTER description SET NOT NULL');
    }
}
