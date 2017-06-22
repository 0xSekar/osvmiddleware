insert into fields_grade_color(metadata_id, `min`, `max`, grade, color, summary)
select * from
   (SELECT metadata_id FROM fields_metadata WHERE table_group = -10 and field_name in ('OverallReturn', 'TotalGain', 'UnrealizedGain', 'UnrealizedGainPercent')) as t1,
   (
      (SELECT null as `min`, 0 as `max`, '' as grade, '#ce355d' as color, 'bad' as summary)
       union (SELECT 0 as `min`, null as `max`, '' as grade, '#04be5b' as color, 'great' as summary)
   ) as m;