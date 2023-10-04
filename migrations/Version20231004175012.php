<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231004175012 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE opening_hours (id INT AUTO_INCREMENT NOT NULL, shop_keeper_id INT DEFAULT NULL, day ENUM(\'monday\', \'tuesday\', \'wednesday\', \'thursday\', \'friday\', \'saturday\', \'sunday\') NOT NULL COMMENT \'(DC2Type:OpeningDaysType)\', opening_time TIME DEFAULT NULL, closing_time TIME DEFAULT NULL, is_open TINYINT(1) NOT NULL, INDEX IDX_2640C10B62CBC908 (shop_keeper_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE opening_hours ADD CONSTRAINT FK_2640C10B62CBC908 FOREIGN KEY (shop_keeper_id) REFERENCES shop_keeper (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE opening_hours DROP FOREIGN KEY FK_2640C10B62CBC908');
        $this->addSql('DROP TABLE opening_hours');
    }
}
