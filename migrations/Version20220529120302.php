<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220529120302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495587335AF1 FOREIGN KEY (lodging_id) REFERENCES lodging (id)');
        $this->addSql('CREATE INDEX IDX_42C8495587335AF1 ON reservation (lodging_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495587335AF1');
        $this->addSql('DROP INDEX IDX_42C8495587335AF1 ON reservation');
    }
}
