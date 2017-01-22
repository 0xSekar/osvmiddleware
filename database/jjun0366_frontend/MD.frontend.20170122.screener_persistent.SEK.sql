ALTER TABLE `screener_persistent`
ADD COLUMN `is_default` TINYINT(1) NULL DEFAULT 0 AFTER `rank`;