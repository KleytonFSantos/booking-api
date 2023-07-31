<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230731012350 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT fk_42c849559d86650f');
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT fk_42c8495535f83ffc');
        $this->addSql('DROP INDEX idx_42c8495535f83ffc');
        $this->addSql('DROP INDEX idx_42c849559d86650f');
        $this->addSql('ALTER TABLE reservation ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD room_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservation DROP user_id_id');
        $this->addSql('ALTER TABLE reservation DROP room_id_id');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495554177093 FOREIGN KEY (room_id) REFERENCES room (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_42C84955A76ED395 ON reservation (user_id)');
        $this->addSql('CREATE INDEX IDX_42C8495554177093 ON reservation (room_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT FK_42C8495554177093');
        $this->addSql('DROP INDEX IDX_42C84955A76ED395');
        $this->addSql('DROP INDEX IDX_42C8495554177093');
        $this->addSql('ALTER TABLE reservation ADD user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD room_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservation DROP user_id');
        $this->addSql('ALTER TABLE reservation DROP room_id');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT fk_42c849559d86650f FOREIGN KEY (user_id_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT fk_42c8495535f83ffc FOREIGN KEY (room_id_id) REFERENCES room (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_42c8495535f83ffc ON reservation (room_id_id)');
        $this->addSql('CREATE INDEX idx_42c849559d86650f ON reservation (user_id_id)');
    }
}
