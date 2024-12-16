<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231028010459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE buy_list_cards_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE payments_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE payments (id INT NOT NULL, users_id INT DEFAULT NULL, reservation_id INT NOT NULL, status VARCHAR(255) NOT NULL, payment_method VARCHAR(255) DEFAULT NULL, transaction_id INT NOT NULL, amount INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_65D29B3267B3B43D ON payments (users_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_65D29B32B83297E7 ON payments (reservation_id)');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B3267B3B43D FOREIGN KEY (users_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B32B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE payments_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE buy_list_cards_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE payments DROP CONSTRAINT FK_65D29B3267B3B43D');
        $this->addSql('ALTER TABLE payments DROP CONSTRAINT FK_65D29B32B83297E7');
        $this->addSql('DROP TABLE payments');
    }
}
