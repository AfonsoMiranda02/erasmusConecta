-- SQL para adicionar colunas de verificação de email à tabela users
-- Execute este script diretamente no phpMyAdmin ou MySQL do WAMP

-- Verificar se as colunas já existem antes de adicionar
SET @dbname = DATABASE();
SET @tablename = "users";
SET @columnname1 = "email_verified_at";
SET @columnname2 = "email_verification_token";

SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname1)
  ) > 0,
  "SELECT 'Coluna email_verified_at já existe' AS message;",
  CONCAT("ALTER TABLE `", @tablename, "` ADD COLUMN `", @columnname1, "` TIMESTAMP NULL DEFAULT NULL AFTER `email`;")
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname2)
  ) > 0,
  "SELECT 'Coluna email_verification_token já existe' AS message;",
  CONCAT("ALTER TABLE `", @tablename, "` ADD COLUMN `", @columnname2, "` VARCHAR(255) NULL DEFAULT NULL AFTER `email_verified_at`;")
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

