<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190108004937 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        //$this->addSql('DROP INDEX uniq_3af346682b36786b');
        //$this->addSql('ALTER TABLE categories ALTER id DROP DEFAULT');
        //$this->addSql('ALTER TABLE categories ALTER parent_id SET NOT NULL');
        //$this->addSql('ALTER TABLE categories ALTER title DROP NOT NULL');
        //$this->addSql('ALTER TABLE nodes ALTER id DROP DEFAULT');
        //$this->addSql('ALTER TABLE nodes ALTER title DROP NOT NULL');
        //$this->addSql('ALTER TABLE nodes ALTER categories_id SET NOT NULL');
        $this->addSql('ALTER TABLE attribute_values DROP CONSTRAINT FK_184662BCB6E62EFA');
        $this->addSql('ALTER TABLE attribute_values ADD CONSTRAINT FK_184662BCB6E62EFA FOREIGN KEY (attribute_id) REFERENCES attributes (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE attribute_values DROP CONSTRAINT fk_184662bcb6e62efa');
        $this->addSql('ALTER TABLE attribute_values ADD CONSTRAINT fk_184662bcb6e62efa FOREIGN KEY (attribute_id) REFERENCES attributes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
       /* $this->addSql('CREATE SEQUENCE categories_id_seq');
        $this->addSql('SELECT setval(\'categories_id_seq\', (SELECT MAX(id) FROM categories))');
        $this->addSql('ALTER TABLE categories ALTER id SET DEFAULT nextval(\'categories_id_seq\')');
        $this->addSql('ALTER TABLE categories ALTER parent_id DROP NOT NULL');
        $this->addSql('ALTER TABLE categories ALTER title SET NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX uniq_3af346682b36786b ON categories (title)');
        $this->addSql('CREATE SEQUENCE nodes_id_seq');
        $this->addSql('SELECT setval(\'nodes_id_seq\', (SELECT MAX(id) FROM nodes))');
        $this->addSql('ALTER TABLE nodes ALTER id SET DEFAULT nextval(\'nodes_id_seq\')');
        $this->addSql('ALTER TABLE nodes ALTER title SET NOT NULL');
        $this->addSql('ALTER TABLE nodes ALTER categories_id DROP NOT NULL'); */
    }
}