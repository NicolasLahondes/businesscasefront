<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211116090808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products ADD fk_brand_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A76DD93D6 FOREIGN KEY (fk_brand_id) REFERENCES brands (id)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A76DD93D6 ON products (fk_brand_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A76DD93D6');
        $this->addSql('DROP INDEX IDX_B3BA5A5A76DD93D6 ON products');
        $this->addSql('ALTER TABLE products DROP fk_brand_id');
    }
}
