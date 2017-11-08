<?php

namespace MyProject\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171107125743 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE build (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, totalprice NUMERIC(10, 2) NOT NULL, description LONGTEXT NOT NULL, created_by VARCHAR(100) NOT NULL, updated_by VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE build_components (build_id INT NOT NULL, component_id INT NOT NULL, INDEX IDX_1C93774417C13F8B (build_id), INDEX IDX_1C937744E2ABAFFF (component_id), PRIMARY KEY(build_id, component_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_8D93D64992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_8D93D649A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_8D93D649C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE component (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, price NUMERIC(10, 2) NOT NULL, description VARCHAR(255) NOT NULL, created_by VARCHAR(100) NOT NULL, updated_by VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE build_components ADD CONSTRAINT FK_1C93774417C13F8B FOREIGN KEY (build_id) REFERENCES build (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE build_components ADD CONSTRAINT FK_1C937744E2ABAFFF FOREIGN KEY (component_id) REFERENCES component (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE build_components DROP FOREIGN KEY FK_1C93774417C13F8B');
        $this->addSql('ALTER TABLE build_components DROP FOREIGN KEY FK_1C937744E2ABAFFF');
        $this->addSql('DROP TABLE build');
        $this->addSql('DROP TABLE build_components');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE component');
    }
}
