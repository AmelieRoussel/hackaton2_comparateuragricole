<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210114215543 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment ADD farmer_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C13481D2B FOREIGN KEY (farmer_id) REFERENCES farmer (id)');
        $this->addSql('CREATE INDEX IDX_9474526C13481D2B ON comment (farmer_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C13481D2B');
        $this->addSql('DROP INDEX IDX_9474526C13481D2B ON comment');
        $this->addSql('ALTER TABLE comment DROP farmer_id');
    }
}
