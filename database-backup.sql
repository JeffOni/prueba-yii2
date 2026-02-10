-- ================================================
-- Script SQL de Respaldo - Sistema de Inventario
-- Base de datos: inventory_system
-- Generado para: Prueba Técnica Yii2
-- ================================================

-- Nota: Este script asume que ya ejecutaste las migraciones de Yii2
-- Para restaurar completamente la base de datos:
-- 1. Ejecuta las migraciones: php yii migrate --interactive=0
-- 2. Ejecuta las migraciones RBAC: php yii migrate --migrationPath=@yii/rbac/migrations --interactive=0
-- 3. Los seeders se ejecutan automáticamente con las migraciones

-- ================================================
-- Estructura de Base de Datos
-- ================================================

SET FOREIGN_KEY_CHECKS=0;

-- Tabla: migration
-- Registra las migraciones ejecutadas
CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla: user
-- Almacena usuarios del sistema
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT 10,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `verification_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Tabla: product
-- Gestiona el inventario de productos
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'Nombre del producto',
  `description` text COMMENT 'Descripción del producto',
  `sku` varchar(100) NOT NULL COMMENT 'Código SKU único',
  `price` decimal(10,2) NOT NULL COMMENT 'Precio del producto',
  `stock` int(11) NOT NULL DEFAULT 0 COMMENT 'Cantidad en stock',
  `status` smallint(6) NOT NULL DEFAULT 1,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku` (`sku`),
  KEY `idx-product-name` (`name`),
  KEY `idx-product-sku` (`sku`),
  CONSTRAINT `chk_stock_positive` CHECK (`stock` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla: audit_log
-- Registra cambios en roles y usuarios
CREATE TABLE IF NOT EXISTS `audit_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `table_name` varchar(100) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `old_value` text,
  `new_value` text,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx-audit-user` (`user_id`),
  KEY `idx-audit-action` (`action`),
  KEY `idx-audit-created` (`created_at`),
  CONSTRAINT `fk-audit-user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tablas RBAC de Yii2
CREATE TABLE IF NOT EXISTS `auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` blob DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `auth_item` (
  `name` varchar(64) NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text DEFAULT NULL,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` blob DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `idx-auth_assignment-user_id` (`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS=1;

-- ================================================
-- INSTRUCCIONES DE RESTAURACIÓN
-- ================================================

-- Para restaurar la base de datos completa:
-- 
-- 1. Importar este script:
--    mysql -u yii2user -p inventory_system < database-backup.sql
-- 
-- 2. Ejecutar migraciones de Yii2:
--    docker-compose exec backend php yii migrate --interactive=0
--    docker-compose exec backend php yii migrate --migrationPath=@yii/rbac/migrations --interactive=0
-- 
-- 3. Los seeders se ejecutan automáticamente e incluyen:
--    - Usuario admin (admin/Admin123!)
--    - Roles: Admin, Editor, Viewer
--    - 9 Permisos RBAC
--    - 15 Productos de ejemplo
--    - Asignación de rol Admin al usuario admin

-- ================================================
-- FIN DEL SCRIPT
-- ================================================
