<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181129142655 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');


        $this->addSql('ALTER TABLE attributes ADD data_type VARCHAR(20) DEFAULT \'integer\' NOT NULL');
        $this->addSql('ALTER TABLE attributes ADD is_active BOOLEAN DEFAULT \'false\' NOT NULL');
        $this->addSql('ALTER TABLE attributes ADD is_multi BOOLEAN DEFAULT \'false\' NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE attributes DROP data_type');
        $this->addSql('ALTER TABLE attributes DROP is_active');
        $this->addSql('ALTER TABLE attributes DROP is_multi');
    }
}
