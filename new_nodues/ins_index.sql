--when the page is loaded
CREATE OR REPLACE VIEW labAssigned AS
	SELECT lab_code
		FROM incharge 
			WHERE incharge_UID = 'emp01';

CREATE OR REPLACE VIEW labHeader AS
	SELECT lab_info.lab_code, department_code, address
		FROM labAssigned, lab_info 
			WHERE lab_info.lab_code = labAssigned.lab_code;

CREATE OR REPLACE VIEW lab_details AS
	SELECT a1.full_form AS lab_name, a1.code as lab_code,
	a2.full_form AS department_name, 
	lab_code, address
		FROM accronym AS a1, accronym AS a2, labHeader
			WHERE a1.code = lab_code
			AND a2.code = department_code;

SELECT * 
	FROM lab_details;

DROP VIEW labAssigned;
DROP VIEW labHeader;
DROP VIEW lab_details;


--Finally while logging out
DROP VIEW labAssigned;
DROP VIEW labHeader;
DROP VIEW lab_details;