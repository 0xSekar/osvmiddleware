ALTER TABLE `portfolio_notes`
ADD COLUMN `ticker_id` BIGINT(20) NOT NULL AFTER `id`;
ALTER TABLE `portfolio_notes`
ADD COLUMN `user_id` VARCHAR(32) NOT NULL AFTER `ticker_id`;
ALTER TABLE `portfolio_notes`
ADD COLUMN `reference` VARCHAR(45) NULL AFTER `note`;
UPDATE portfolio_notes as n INNER JOIN portfolio_stocks as s ON n.pstock_id = s.pstock_id
SET n.ticker_id = s.ticker_id;
UPDATE portfolio_notes as n INNER JOIN portfolio_stocks as s ON n.pstock_id = s.pstock_id
INNER JOIN portfolio_persistent as p ON p.id = s.portfolio_id
SET n.user_id = p.user_id;
ALTER TABLE portfolio_notes
DROP COLUMN pstock_id;