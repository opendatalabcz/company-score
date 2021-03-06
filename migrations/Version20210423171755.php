<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210423171755 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7D3656A4F85E0677 ON account (username)');
        $this->addSql('DROP INDEX UNIQ_560581FD824F7A6');
        $this->addSql('DROP INDEX UNIQ_560581FD3D647A44');
        $this->addSql('DROP INDEX UNIQ_560581FD9CE9FB17');
        $this->addSql('DROP INDEX UNIQ_560581FD2E9F375');
        $this->addSql('DROP INDEX UNIQ_560581FD77BD680F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__firm AS SELECT id, zakladni_test_id, test_jednatelu_id, bonusovy_test_id, test_subjektu_id, test_domeny_id, name, ico, sidlo, result FROM firm');
        $this->addSql('DROP TABLE firm');
        $this->addSql('CREATE TABLE firm (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, zakladni_test_id INTEGER DEFAULT NULL, test_jednatelu_id INTEGER DEFAULT NULL, bonusovy_test_id INTEGER DEFAULT NULL, test_subjektu_id INTEGER DEFAULT NULL, test_domeny_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, ico VARCHAR(8) NOT NULL COLLATE BINARY, sidlo VARCHAR(255) NOT NULL COLLATE BINARY, result INTEGER DEFAULT NULL, CONSTRAINT FK_560581FD824F7A6 FOREIGN KEY (zakladni_test_id) REFERENCES zakladni_test (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_560581FD3D647A44 FOREIGN KEY (test_jednatelu_id) REFERENCES test_jednatelu (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_560581FD9CE9FB17 FOREIGN KEY (bonusovy_test_id) REFERENCES bonusovy_test (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_560581FD2E9F375 FOREIGN KEY (test_subjektu_id) REFERENCES test_subjektu (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_560581FD77BD680F FOREIGN KEY (test_domeny_id) REFERENCES test_domeny (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO firm (id, zakladni_test_id, test_jednatelu_id, bonusovy_test_id, test_subjektu_id, test_domeny_id, name, ico, sidlo, result) SELECT id, zakladni_test_id, test_jednatelu_id, bonusovy_test_id, test_subjektu_id, test_domeny_id, name, ico, sidlo, result FROM __temp__firm');
        $this->addSql('DROP TABLE __temp__firm');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_560581FD824F7A6 ON firm (zakladni_test_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_560581FD3D647A44 ON firm (test_jednatelu_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_560581FD9CE9FB17 ON firm (bonusovy_test_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_560581FD2E9F375 ON firm (test_subjektu_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_560581FD77BD680F ON firm (test_domeny_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP INDEX UNIQ_560581FD824F7A6');
        $this->addSql('DROP INDEX UNIQ_560581FD3D647A44');
        $this->addSql('DROP INDEX UNIQ_560581FD9CE9FB17');
        $this->addSql('DROP INDEX UNIQ_560581FD2E9F375');
        $this->addSql('DROP INDEX UNIQ_560581FD77BD680F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__firm AS SELECT id, zakladni_test_id, test_jednatelu_id, bonusovy_test_id, test_subjektu_id, test_domeny_id, name, ico, sidlo, result FROM firm');
        $this->addSql('DROP TABLE firm');
        $this->addSql('CREATE TABLE firm (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, zakladni_test_id INTEGER DEFAULT NULL, test_jednatelu_id INTEGER DEFAULT NULL, bonusovy_test_id INTEGER DEFAULT NULL, test_subjektu_id INTEGER DEFAULT NULL, test_domeny_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, ico VARCHAR(8) NOT NULL, sidlo VARCHAR(255) NOT NULL, result INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO firm (id, zakladni_test_id, test_jednatelu_id, bonusovy_test_id, test_subjektu_id, test_domeny_id, name, ico, sidlo, result) SELECT id, zakladni_test_id, test_jednatelu_id, bonusovy_test_id, test_subjektu_id, test_domeny_id, name, ico, sidlo, result FROM __temp__firm');
        $this->addSql('DROP TABLE __temp__firm');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_560581FD824F7A6 ON firm (zakladni_test_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_560581FD3D647A44 ON firm (test_jednatelu_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_560581FD9CE9FB17 ON firm (bonusovy_test_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_560581FD2E9F375 ON firm (test_subjektu_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_560581FD77BD680F ON firm (test_domeny_id)');
    }
}
