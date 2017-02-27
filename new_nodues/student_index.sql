CREATE OR REPLACE VIEW student_pending AS
	SELECT dueID, amount, description,
		generated_time, employee_uID, lab_code, modified_time
		FROM dues
			WHERE status = 'P'
			AND entry_number = '2014CS10258';

CREATE OR REPLACE VIEW labDues AS
	SELECT DISTINCT lab_code, employee_uID
		FROM student_pending;

CREATE OR REPLACE VIEW labHeader AS
	SELECT DISTINCT lab_info.lab_code, department_code, address
		FROM lab_info, labDues
			WHERE lab_info.lab_code = labDues.lab_code;

CREATE OR REPLACE VIEW lab_details AS
	SELECT a1.full_form AS lab_name, 
	a2.full_form AS department_name,
	lab_code, address
		FROM accronym AS a1, accronym AS a2, labHeader
			WHERE a1.code = lab_code
			AND a2.code = department_code;

CREATE OR REPLACE VIEW employee_Details AS
	SELECT DISTINCT name, employee_uID from user_table, labDues
		WHERE user_table.uID = employee_uID;

SELECT description,
	generated_time, name, amount , lab_name
	FROM lab_details, employee_Details, student_pending
		WHERE student_pending.lab_code = lab_details.lab_code
		AND student_pending.employee_uID = employee_Details.employee_uID ORDER BY generated_time;

drop view student_pending, labDues, labHeader, lab_details, employee_Details;

--List of dues Requested
CREATE OR REPLACE VIEW student_requested AS
	SELECT dueID, amount, description,
		generated_time, employee_uID, lab_code, modified_time
		FROM dues
			WHERE status = 'R'
			AND entry_number = '2014CS10258';

CREATE OR REPLACE VIEW req_labDues AS
	SELECT DISTINCT lab_code, employee_uID
		FROM student_requested;

CREATE OR REPLACE VIEW req_labHeader AS
	SELECT DISTINCT lab_info.lab_code, department_code, address
		FROM lab_info, req_labDues
			WHERE lab_info.lab_code = req_labDues.lab_code;

CREATE OR REPLACE VIEW req_lab_details AS
	SELECT a1.full_form AS lab_name, 
	a2.full_form AS department_name,
	lab_code, address
		FROM accronym AS a1, accronym AS a2, req_labHeader
			WHERE a1.code = lab_code
			AND a2.code = department_code;

CREATE OR REPLACE VIEW req_employee_Details AS
	SELECT DISTINCT name, employee_uID 
		FROM user_table, req_labDues
			WHERE user_table.uID = employee_uID;

CREATE OR REPLACE VIEW dueIDs AS
	SELECT dueID
		FROM student_requested;

CREATE OR REPLACE VIEW request_details AS
	SELECT duesra.dueID, requested_time, requested_comment
		FROM duesra, dueIDs
			WHERE dueIDs.dueID = duesra.dueID;

SELECT description,
	requested_time, requested_comment , amount , lab_name
	FROM req_lab_details, request_details, student_requested
		WHERE student_requested.lab_code = req_lab_details.lab_code
		AND request_details.dueID = student_requested.dueID ORDER BY generated_time;

drop view student_requested, req_labDues, req_labHeader, req_lab_details, req_employee_Details, request_details, dueIDs;

-- to show dues that are already updated
CREATE OR REPLACE VIEW student_approved AS
	SELECT dueID, amount, description,
		generated_time, employee_uID, lab_code, modified_time
		FROM dues
			WHERE status = 'A'
			AND entry_number = '2014CS10258';

CREATE OR REPLACE VIEW approved_labDues AS
	SELECT DISTINCT lab_code, employee_uID
		FROM student_approved;

CREATE OR REPLACE VIEW approved_labHeader AS
	SELECT DISTINCT lab_info.lab_code, department_code, address
		FROM lab_info, approved_labDues
			WHERE lab_info.lab_code = approved_labDues.lab_code;

CREATE OR REPLACE VIEW approved_lab_details AS
	SELECT a1.full_form AS lab_name, 
	a2.full_form AS department_name,
	lab_code, address
		FROM accronym AS a1, accronym AS a2, approved_labHeader
			WHERE a1.code = lab_code
			AND a2.code = department_code;

CREATE OR REPLACE VIEW approved_employee_Details AS
	SELECT DISTINCT name, employee_uID 
		FROM user_table, approved_labDues
			WHERE user_table.uID = employee_uID;

CREATE OR REPLACE VIEW dueIDs AS
	SELECT dueID
		FROM student_approved;

CREATE OR REPLACE VIEW request_details AS
	SELECT duesra.dueID, approved_time, approved_comment
		FROM duesra, dueIDs
			WHERE dueIDs.dueID = duesra.dueID;

SELECT description,
	approved_time, approved_comment , amount , lab_name
	FROM approved_lab_details, request_details, student_approved
		WHERE student_approved.lab_code = approved_lab_details.lab_code
		AND request_details.dueID = student_approved.dueID ORDER BY generated_time;

drop view student_approved, approved_labDues, approved_labHeader, approved_lab_details, approved_employee_Details, request_details, dueIDs;

--to request a due approval
UPDATE dues SET
	status = 'R'
		WHERE dueID = 1;
INSERT INTO duesra (
	dueID,
	requested_comment,
	requested_time) VALUES(
	1,
	'new request',
	now());