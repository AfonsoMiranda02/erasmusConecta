-- SQL para criar tabela de registos pendentes
-- Execute este script diretamente no phpMyAdmin ou MySQL do WAMP

CREATE TABLE IF NOT EXISTS `pending_registrations` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `num_processo` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `codigo_mobilidade` varchar(255) DEFAULT NULL,
  `documento_path` varchar(255) DEFAULT NULL,
  `verification_code` varchar(6) NOT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pending_registrations_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

