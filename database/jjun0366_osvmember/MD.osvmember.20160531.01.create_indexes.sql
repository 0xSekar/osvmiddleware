Create index eol_reports_reportdate on eol_reports(reportdate);
Create index eol_reports_reporttype_reportdate_ticker on eol_reports(reporttype, reportdate, ticker);

Create index gf_reports_reportdate on gf_reports(reportdate);
Create index gf_reports_reporttype_reportdate_ticker on gf_reports(reporttype, reportdate, ticker);

Create index eol_info_idreport_name on eol_info(idreport, name);
