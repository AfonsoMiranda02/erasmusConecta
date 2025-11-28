-- SQL SIMPLES para adicionar colunas de verificação de email à tabela users
-- Execute este script diretamente no phpMyAdmin ou MySQL do WAMP
-- Se der erro de coluna já existe, ignora o erro

ALTER TABLE `users` 
ADD COLUMN `email_verified_at` TIMESTAMP NULL DEFAULT NULL AFTER `email`,
ADD COLUMN `email_verification_token` VARCHAR(255) NULL DEFAULT NULL AFTER `email_verified_at`;

