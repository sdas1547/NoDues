CREATE OR REPLACE VIEW dues_details AS
	SELECT dueID,amount,description, modified_time
		generated_time, status, entry_number
		FROM dues
			WHERE employee_code = 'emp01'
			AND lab_code = 'col01';

CREATE OR REPLACE VIEW duesr_details AS
	SELECT dues_details.dueID, requested_time,
		requested_comment, entry_number, amount
		FROM dues_details, duesra
			WHERE dues_details.dueID = duesra.dueID
			AND status = 'R';

CREATE OR REPLACE VIEW duesa_details AS
	SELECT dues_details.dueID, approved_time,
		approved_comment , entry_number, amount	
		FROM dues_details, duesra
			WHERE dues_details.dueID = duesra.dueID
			AND status = 'A';

--Pendinng sql
SELECT dueID, entry_number,amount,
	generated_time, description
	FROM dues_details
		WHERE status = "P";

--cancelled details
SELECT dueID, entry_number,amount,
	modified_time, description,
	FROM dues_details
		WHERE status = "C";

--Clearence Request sql
--QUERY--
SELECT * 
	FROM duesr_details;


--Approved sql
--QUERY--
SELECT *
	FROM duesa_details;


--Detail view of requested details
CREATE VIEW requestedView1 AS
	SELECT *
		FROM dues_details
			WHERE dueID = 1;
CREATE VIEW requestedView2 AS
	SELECT *
		FROM duesr_details
			WHERE dueID = 1;
--Query--
SELECT *
	FROM requestedView1, requestedView2;
--Clearence--
DROP VIEW requestedView1;
DROP VIEW requestedView2;


--Detail view of approved details
CREATE VIEW approvedView1 AS
	SELECT *
		FROM dues_details
			WHERE dueID = 1;
CREATE VIEW approvedView2 AS
	SELECT *
		FROM duesa_details
			WHERE dueID = 1;
--Query--
SELECT *
	FROM approvedView1, approvedView2;
--Clearence--
DROP VIEW approvedView1;
DROP VIEW approvedView2;


--details of pending view
--Query--
SELECT *
	FROM dues_details
		WHERE dueID = 1;

--Query to add a new Record
INSERT INTO dues (
	amount, 
	description, 
	generated_time, 
	modified_time, 
	status, 
	entry_number,
	employee_uID,
	lab_code ) VALUES (
	10.0,
	'Lan Port brokege',
	now(),
	now(),
	'P',
	'2014CS10258',
	'emp01',
	'col01');


--Query to Edit table
UPDATE dues SET
	amount = 11.0,
	description = 'new_description',
	modified_time = now()
		WHERE dueID = 1;


--Query to cancel a request
UPDATE dues SET
	status = 'C'
		WHERE dueID = 1;


--Query to approve a due
UPDATE dues SET
	status = 'A'
		WHERE dueID = 1;
UPDATE duesra SET
	approved_comment = 'new comment',
	approved_time = now()
		WHERE dueID = 1