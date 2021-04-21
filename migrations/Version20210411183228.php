<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210411183228 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_560581FD75E9ACE4');
        $this->addSql('DROP INDEX UNIQ_560581FDD23C872B');
        $this->addSql('DROP INDEX UNIQ_560581FD51B612F3');
        $this->addSql('DROP INDEX UNIQ_560581FD8EDDD42C');
        $this->addSql('DROP INDEX UNIQ_560581FDDC4A7741');
        $this->addSql('CREATE TEMPORARY TABLE __temp__firm AS SELECT id, zakladni_test_id_id, test_jednatelu_id_id, bonusovy_test_id_id, test_subjektu_id_id, test_domeny_id_id, name, ico, sidlo, result FROM firm');
        $this->addSql('DROP TABLE firm');
        $this->addSql('CREATE TABLE firm (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, test_jednatelu_id_id INTEGER DEFAULT NULL, bonusovy_test_id_id INTEGER DEFAULT NULL, test_subjektu_id_id INTEGER DEFAULT NULL, test_domeny_id_id INTEGER DEFAULT NULL, zakladni_test_id_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, ico VARCHAR(8) NOT NULL COLLATE BINARY, sidlo VARCHAR(255) NOT NULL COLLATE BINARY, result INTEGER NOT NULL, CONSTRAINT FK_560581FDDC4A7741 FOREIGN KEY (zakladni_test_id_id) REFERENCES zakladni_test (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_560581FD8EDDD42C FOREIGN KEY (test_jednatelu_id_id) REFERENCES test_jednatelu (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_560581FD51B612F3 FOREIGN KEY (bonusovy_test_id_id) REFERENCES bonusovy_test (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_560581FDD23C872B FOREIGN KEY (test_subjektu_id_id) REFERENCES test_subjektu (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_560581FD75E9ACE4 FOREIGN KEY (test_domeny_id_id) REFERENCES test_domeny (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO firm (id, zakladni_test_id_id, test_jednatelu_id_id, bonusovy_test_id_id, test_subjektu_id_id, test_domeny_id_id, name, ico, sidlo, result) SELECT id, zakladni_test_id_id, test_jednatelu_id_id, bonusovy_test_id_id, test_subjektu_id_id, test_domeny_id_id, name, ico, sidlo, result FROM __temp__firm');
        $this->addSql('DROP TABLE __temp__firm');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_560581FD75E9ACE4 ON firm (test_domeny_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_560581FDD23C872B ON firm (test_subjektu_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_560581FD51B612F3 ON firm (bonusovy_test_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_560581FD8EDDD42C ON firm (test_jednatelu_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_560581FDDC4A7741 ON firm (zakladni_test_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_560581FDDC4A7741');
        $this->addSql('DROP INDEX UNIQ_560581FD8EDDD42C');
        $this->addSql('DROP INDEX UNIQ_560581FD51B612F3');
        $this->addSql('DROP INDEX UNIQ_560581FDD23C872B');
        $this->addSql('DROP INDEX UNIQ_560581FD75E9ACE4');
        $this->addSql('CREATE TEMPORARY TABLE __temp__firm AS SELECT id, zakladni_test_id_id, test_jednatelu_id_id, bonusovy_test_id_id, test_subjektu_id_id, test_domeny_id_id, name, ico, sidlo, result FROM firm');
        $this->addSql('DROP TABLE firm');
        $this->addSql('CREATE TABLE firm (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, test_jednatelu_id_id INTEGER DEFAULT NULL, bonusovy_test_id_id INTEGER DEFAULT NULL, test_subjektu_id_id INTEGER DEFAULT NULL, test_domeny_id_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, ico VARCHAR(8) NOT NULL, sidlo VARCHAR(255) NOT NULL, result INTEGER NOT NULL, zakladni_test_id_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO firm (id, zakladni_test_id_id, test_jednatelu_id_id, bonusovy_test_id_id, test_subjektu_id_id, test_domeny_id_id, name, ico, sidlo, result) SELECT id, zakladni_test_id_id, test_jednatelu_id_id, bonusovy_test_id_id, test_subjektu_id_id, test_domeny_id_id, name, ico, sidlo, result FROM __temp__firm');
        $this->addSql('DROP TABLE __temp__firm');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_560581FDDC4A7741 ON firm (zakladni_test_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_560581FD8EDDD42C ON firm (test_jednatelu_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_560581FD51B612F3 ON firm (bonusovy_test_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_560581FDD23C872B ON firm (test_subjektu_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_560581FD75E9ACE4 ON firm (test_domeny_id_id)');
    }
}
