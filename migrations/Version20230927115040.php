<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230927115040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answers ADD questions_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE answers ADD CONSTRAINT FK_50D0C606BCB134CE FOREIGN KEY (questions_id) REFERENCES questions (id)');
        $this->addSql('ALTER TABLE answers ADD CONSTRAINT FK_50D0C606A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_50D0C606BCB134CE ON answers (questions_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_50D0C606A76ED395 ON answers (user_id)');
        $this->addSql('ALTER TABLE forms ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE forms ADD CONSTRAINT FK_FD3F1BF7A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_FD3F1BF7A76ED395 ON forms (user_id)');
        $this->addSql('ALTER TABLE questions ADD forms_id INT NOT NULL');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D5C99A463F FOREIGN KEY (forms_id) REFERENCES forms (id)');
        $this->addSql('CREATE INDEX IDX_8ADC54D5C99A463F ON questions (forms_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answers DROP FOREIGN KEY FK_50D0C606BCB134CE');
        $this->addSql('ALTER TABLE answers DROP FOREIGN KEY FK_50D0C606A76ED395');
        $this->addSql('DROP INDEX IDX_50D0C606BCB134CE ON answers');
        $this->addSql('DROP INDEX UNIQ_50D0C606A76ED395 ON answers');
        $this->addSql('ALTER TABLE answers DROP questions_id, DROP user_id');
        $this->addSql('ALTER TABLE forms DROP FOREIGN KEY FK_FD3F1BF7A76ED395');
        $this->addSql('DROP INDEX IDX_FD3F1BF7A76ED395 ON forms');
        $this->addSql('ALTER TABLE forms DROP user_id');
        $this->addSql('ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D5C99A463F');
        $this->addSql('DROP INDEX IDX_8ADC54D5C99A463F ON questions');
        $this->addSql('ALTER TABLE questions DROP forms_id');
    }
}
