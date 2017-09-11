CREATE TABLE `jjun0366_frontend`.`category_video_training` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`));

ALTER TABLE `jjun0366_frontend`.`tutorial_video`
ADD COLUMN `category` BIGINT(20) NULL AFTER `tab`,
ADD INDEX `fk_tutorial_video_1_idx` (`category` ASC);
ALTER TABLE `jjun0366_frontend`.`tutorial_video`
ADD CONSTRAINT `fk_tutorial_video_1`
FOREIGN KEY (`category`)
  REFERENCES `jjun0366_frontend`.`category_video_training` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;