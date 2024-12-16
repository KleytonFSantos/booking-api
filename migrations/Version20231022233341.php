<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231022233341 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE booking_review (id INT NOT NULL, users_id INT NOT NULL, reservation_id INT NOT NULL, rating INT NOT NULL, review VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C2D9BA3867B3B43D ON booking_review (users_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C2D9BA38B83297E7 ON booking_review (reservation_id)');
        $this->addSql('ALTER TABLE booking_review ADD CONSTRAINT FK_C2D9BA3867B3B43D FOREIGN KEY (users_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking_review ADD CONSTRAINT FK_C2D9BA38B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE booking_review DROP CONSTRAINT FK_C2D9BA3867B3B43D');
        $this->addSql('ALTER TABLE booking_review DROP CONSTRAINT FK_C2D9BA38B83297E7');
        $this->addSql('DROP TABLE booking_review');
    }
}
