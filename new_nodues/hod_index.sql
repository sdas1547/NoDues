$lab_data = "labdata".$admin_id;
$lab_data_sql1 = "CREATE OR REPLACE VIEW $lab_data AS
					SELECT lab_code, department_code, address
						FROM lab_info
							WHERE hod_UID = '$admin_id';";
$lab_data_sql = "SELECT a1.name AS department_name,
					a2.name AS lab_name, address
					FROM $lab_data, accronym as a1, accronym as a2
						WHERE a1.code = lab_code
						AND a2.code = department_code;"
$con->query(lab_data_sql1);
$lab_data_result = $con->query(lab_data_sql);
