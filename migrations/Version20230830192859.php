<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230830192859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE card_favoris_card (card_favoris_id INT NOT NULL, card_id INT NOT NULL, INDEX IDX_B5AEACB13D9A1A15 (card_favoris_id), INDEX IDX_B5AEACB14ACC9A20 (card_id), PRIMARY KEY(card_favoris_id, card_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE card_favoris_card ADD CONSTRAINT FK_B5AEACB13D9A1A15 FOREIGN KEY (card_favoris_id) REFERENCES card_favoris (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE card_favoris_card ADD CONSTRAINT FK_B5AEACB14ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE card_favoris ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE card_favoris ADD CONSTRAINT FK_DE294E44A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_DE294E44A76ED395 ON card_favoris (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE card_favoris_card DROP FOREIGN KEY FK_B5AEACB13D9A1A15');
        $this->addSql('ALTER TABLE card_favoris_card DROP FOREIGN KEY FK_B5AEACB14ACC9A20');
        $this->addSql('DROP TABLE card_favoris_card');
        $this->addSql('ALTER TABLE card_favoris DROP FOREIGN KEY FK_DE294E44A76ED395');
        $this->addSql('DROP INDEX IDX_DE294E44A76ED395 ON card_favoris');
        $this->addSql('ALTER TABLE card_favoris DROP user_id');
    }
}
