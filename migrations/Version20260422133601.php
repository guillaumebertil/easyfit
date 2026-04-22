<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260422133601 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE color (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, hex_code VARCHAR(7) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, price NUMERIC(5, 2) NOT NULL, is_featured TINYINT NOT NULL, is_active TINYINT NOT NULL, category_id INT NOT NULL, INDEX IDX_D34A04AD12469DE2 (category_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE product_size (product_id INT NOT NULL, size_id INT NOT NULL, INDEX IDX_7A2806CB4584665A (product_id), INDEX IDX_7A2806CB498DA827 (size_id), PRIMARY KEY (product_id, size_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE product_color (product_id INT NOT NULL, color_id INT NOT NULL, INDEX IDX_C70A33B54584665A (product_id), INDEX IDX_C70A33B57ADA1FB5 (color_id), PRIMARY KEY (product_id, color_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE product_image (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) NOT NULL, alt_text VARCHAR(255) DEFAULT NULL, product_id INT NOT NULL, INDEX IDX_64617F034584665A (product_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE product_variant (id INT AUTO_INCREMENT NOT NULL, stock INT NOT NULL, product_id INT NOT NULL, size_id INT NOT NULL, color_id INT NOT NULL, INDEX IDX_209AA41D4584665A (product_id), INDEX IDX_209AA41D498DA827 (size_id), INDEX IDX_209AA41D7ADA1FB5 (color_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE size (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(10) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product_size ADD CONSTRAINT FK_7A2806CB4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_size ADD CONSTRAINT FK_7A2806CB498DA827 FOREIGN KEY (size_id) REFERENCES size (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_color ADD CONSTRAINT FK_C70A33B54584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_color ADD CONSTRAINT FK_C70A33B57ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_image ADD CONSTRAINT FK_64617F034584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_variant ADD CONSTRAINT FK_209AA41D4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_variant ADD CONSTRAINT FK_209AA41D498DA827 FOREIGN KEY (size_id) REFERENCES size (id)');
        $this->addSql('ALTER TABLE product_variant ADD CONSTRAINT FK_209AA41D7ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE product_size DROP FOREIGN KEY FK_7A2806CB4584665A');
        $this->addSql('ALTER TABLE product_size DROP FOREIGN KEY FK_7A2806CB498DA827');
        $this->addSql('ALTER TABLE product_color DROP FOREIGN KEY FK_C70A33B54584665A');
        $this->addSql('ALTER TABLE product_color DROP FOREIGN KEY FK_C70A33B57ADA1FB5');
        $this->addSql('ALTER TABLE product_image DROP FOREIGN KEY FK_64617F034584665A');
        $this->addSql('ALTER TABLE product_variant DROP FOREIGN KEY FK_209AA41D4584665A');
        $this->addSql('ALTER TABLE product_variant DROP FOREIGN KEY FK_209AA41D498DA827');
        $this->addSql('ALTER TABLE product_variant DROP FOREIGN KEY FK_209AA41D7ADA1FB5');
        $this->addSql('DROP TABLE color');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_size');
        $this->addSql('DROP TABLE product_color');
        $this->addSql('DROP TABLE product_image');
        $this->addSql('DROP TABLE product_variant');
        $this->addSql('DROP TABLE size');
    }
}
