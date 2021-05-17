<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210411160457 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bonusovy_test (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, pocet_provozoven INTEGER DEFAULT NULL, ochranne_znamky VARCHAR(3) DEFAULT NULL, rust_zakladniho_kapitala VARCHAR(3) DEFAULT NULL, aktualni_nabidky_prace VARCHAR(3) DEFAULT NULL)');
        $this->addSql('CREATE TABLE firm (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, zakladni_test_id_id INTEGER NOT NULL, test_jednatelu_id_id INTEGER DEFAULT NULL, bonusovy_test_id_id INTEGER DEFAULT NULL, test_subjektu_id_id INTEGER DEFAULT NULL, test_domeny_id_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, ico VARCHAR(8) NOT NULL, sidlo VARCHAR(255) NOT NULL, result INTEGER NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_560581FDDC4A7741 ON firm (zakladni_test_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_560581FD8EDDD42C ON firm (test_jednatelu_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_560581FD51B612F3 ON firm (bonusovy_test_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_560581FDD23C872B ON firm (test_subjektu_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_560581FD75E9ACE4 ON firm (test_domeny_id_id)');
        $this->addSql('CREATE TABLE test_domeny (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, status VARCHAR(3) NOT NULL, pocet_let_v_provozu INTEGER NOT NULL, posledni_modifikace INTEGER NOT NULL, result INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE test_jednatelu (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, pocet_jednatelu INTEGER NOT NULL, pravni_forma VARCHAR(255) DEFAULT NULL, pocet_jinych_subjektu INTEGER NOT NULL, pocet_jinych_subjektu_v_likvidaci INTEGER NOT NULL, netypycky_vek_jednatele VARCHAR(3) DEFAULT NULL, insolvence VARCHAR(3) DEFAULT NULL, bydleni_mimo_eu VARCHAR(3) DEFAULT NULL, result INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE test_subjektu (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, pocet_zamestnancu INTEGER DEFAULT NULL, pocet_let_na_trhu INTEGER NOT NULL, nespolehlivy_platce VARCHAR(3) DEFAULT NULL, jine_subjekty_na_sidle INTEGER DEFAULT NULL, result INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE zakladni_test (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, status VARCHAR(3) NOT NULL, result INTEGER NOT NULL)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE bonusovy_test');
        $this->addSql('DROP TABLE firm');
        $this->addSql('DROP TABLE test_domeny');
        $this->addSql('DROP TABLE test_jednatelu');
        $this->addSql('DROP TABLE test_subjektu');
        $this->addSql('DROP TABLE zakladni_test');
    }
}
