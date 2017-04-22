UPDATE fields_metadata SET good_increase = false WHERE metadata_id in (183, 186, 185, 418, 423, 420, 415,417 , 419,  420, 421, 424, 426, 433, 436, 437, 438, 444, 445, 446, 447, 448, 449, 450, 451,
452, 453, 454, 455, 456, 457, 458 , 359, 373, 371, 295, 370, 386, 379, 389, 343, 264, 384, 361, 357, 346, 320, 256, 334, 243,
252, 254, 269, 253, 307, 306, 273, 275, 337);

CREATE TABLE `fields_grade_color` (
   `id` BIGINT NOT NULL AUTO_INCREMENT,
   `metadata_id` BIGINT NOT NULL,
   `min` VARCHAR(45) NULL,
   `max` VARCHAR(45) NULL,
   `grade` VARCHAR(45) NULL,
   `color` VARCHAR(45) NULL,
   `summary` VARCHAR(45) NULL,
   PRIMARY KEY (`id`),
   CONSTRAINT `fk_fields_grade_color_1`
   FOREIGN KEY (`metadata_id`)
   REFERENCES `fields_metadata` (`metadata_id`)
   ON DELETE NO ACTION
   ON UPDATE NO ACTION
);

insert into fields_grade_color(metadata_id, `min`, `max`, grade, color, summary)
select * from
   (SELECT metadata_id FROM fields_metadata where field_name in ('AS','VT','GT','QT')) as t1,
   (
      (SELECT null as `min`, 50 as `max`, 'F' as grade, '#ce355d' as color, 'bad' as summary)
      union (SELECT 50 as `min`, 65 as `max`, 'D' as grade, '#ce355d' as color, 'warning' as summary)
      union (SELECT 65 as `min`, 75 as `max`, 'C' as grade, '#FF9E01' as color, 'fair' as summary)
      union (SELECT 75 as `min`, 85 as `max`, 'B' as grade, '#2a8ced' as color, 'good' as summary)
      union (SELECT 85 as `min`, null as `max`, 'A' as grade, '#04be5b' as color, 'great' as summary)
   ) as m;

insert into fields_grade_color(metadata_id, `min`, `max`, grade, color, summary)
select * from
	(SELECT metadata_id FROM fields_metadata where field_name in ('AS_grade', 'V_grade', 'G_grade', 'Q_grade')) as t1,
    (
		(SELECT 'A' as `min`, 'A' as `max`, 'A' as grade, '#04be5b' as color, 'great' as summary)
       union (SELECT 'B' as `min`, 'B' as `max`, 'B' as grade, '#2a8ced' as color, 'good' as summary)
       union (SELECT 'C' as `min`, 'C' as `max`, 'C' as grade, '#FF9E01' as color, 'fair' as summary)
       union (SELECT 'D' as `min`, 'D' as `max`, 'D' as grade, '#ce355d' as color, 'warning' as summary)
       union (SELECT 'F' as `min`, 'F' as `max`, 'F' as grade, '#ce355d' as color, 'bad' as summary)
    ) as m;

insert into fields_grade_color(metadata_id, `min`, `max`, grade, color, summary)
select * from
   (SELECT metadata_id FROM fields_metadata where field_name in ('BM5','BM8')) as t1,
   (
       (SELECT -1.78 as `min`, null as `max`, '' as grade, '#CE355D' as color, 'bad' as summary)
      union (SELECT null as `min`, -1.78 as `max`, '' as grade, '#04be5b' as color, 'good' as summary)
   ) as m;

insert into fields_grade_color(metadata_id, `min`, `max`, grade, color, summary)
select * from
   (SELECT metadata_id FROM fields_metadata where field_name in ('AltmanZNormal')) as t1,
   (
       (SELECT 3 as `min`, null as `max`, '' as grade, '#04be5b' as color, 'great' as summary)
      union (SELECT 1.8 as `min`, 3 as `max`, '' as grade, '#2a8ced' as color, 'good' as summary)
      union (SELECT null as `min`, 1.8 as `max`, '' as grade, '#ce355d' as color, 'bad' as summary)
   ) as m;

insert into fields_grade_color(metadata_id, `min`, `max`, grade, color, summary)
select * from
   (SELECT metadata_id FROM fields_metadata where field_name in ('AltmanZRevised')) as t1,
   (
       (SELECT 2.6 as `min`, null as `max`, '' as grade, '#04be5b' as color, 'great' as summary)
      union (SELECT 1.1 as `min`, 2.6 as `max`, '' as grade, '#2a8ced' as color, 'good' as summary)
      union (SELECT null as `min`, 1.1 as `max`, '' as grade, '#ce355d' as color, 'bad' as summary)
   ) as m;

insert into fields_grade_color(metadata_id, `min`, `max`, grade, color, summary)
select * from
   (SELECT metadata_id FROM fields_metadata where field_name in ('pioTotal')) as t1,
   (
       (SELECT 7 as `min`, null as `max`, '' as grade, '#04be5b' as color, 'great' as summary)
      union (SELECT 5 as `min`, 7 as `max`, '' as grade, '#2a8ced' as color, 'good' as summary)
      union (SELECT null as `min`, 5 as `max`, '' as grade, '#ce355d' as color, 'bad' as summary)
   ) as m;