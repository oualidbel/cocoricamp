<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220529095542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, lodging_id_id INT NOT NULL, client_firstname VARCHAR(255) NOT NULL, client_lastname VARCHAR(255) NOT NULL, client_email VARCHAR(255) NOT NULL, client_phone VARCHAR(255) NOT NULL, client_address VARCHAR(255) NOT NULL, reservation_checkin DATETIME NOT NULL, reservation_checkout DATETIME NOT NULL, reservation_price INT NOT NULL, UNIQUE INDEX UNIQ_42C849552DC30898 (lodging_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849552DC30898 FOREIGN KEY (lodging_id_id) REFERENCES lodging (id)');
        $this->addSql('DROP INDEX id ON location');
        $this->addSql('ALTER TABLE lodging DROP FOREIGN KEY lodging_ibfk_1');
        $this->addSql('DROP INDEX location_id ON lodging');
        $this->addSql('CREATE INDEX IDX_8D35182A64D218E ON lodging (location_id)');
        $this->addSql('ALTER TABLE lodging ADD CONSTRAINT lodging_ibfk_1 FOREIGN KEY (location_id) REFERENCES location (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE reservation');
        $this->addSql('CREATE INDEX id ON location (id)');
        $this->addSql('ALTER TABLE lodging DROP FOREIGN KEY FK_8D35182A64D218E');
        $this->addSql('DROP INDEX idx_8d35182a64d218e ON lodging');
        $this->addSql('CREATE INDEX location_id ON lodging (location_id)');
        $this->addSql('ALTER TABLE lodging ADD CONSTRAINT FK_8D35182A64D218E FOREIGN KEY (location_id) REFERENCES location (id)');
    }
}
