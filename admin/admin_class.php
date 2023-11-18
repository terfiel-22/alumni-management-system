<?php
session_start();
ini_set('display_errors', 1);
class Action
{
	private $db;

	public function __construct()
	{
		ob_start();
		include 'db_connect.php';
		$this->db = $conn;
	}

	function __destruct()
	{

		$this->db->close();
		ob_end_flush();
	}

	function login()
	{

		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where username = '" . $username . "' and password = '" . md5($password) . "' ");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			if ($_SESSION['login_type'] != 1) {
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				return 2;
				exit;
			}
			return 1;
		} else {
			return 3;
		}
	}
	function login2()
	{

		extract($_POST);
		if (isset($email))
			$username = $email;
		$qry = $this->db->query("SELECT * FROM users where username = '" . $username . "' and password = '" . md5($password) . "' ");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			if ($_SESSION['login_alumnus_id'] > 0) {
				$bio = $this->db->query("SELECT * FROM alumnus_bio where id = " . $_SESSION['login_alumnus_id']);
				if ($bio->num_rows > 0) {
					foreach ($bio->fetch_array() as $key => $value) {
						if ($key != 'passwors' && !is_numeric($key))
							$_SESSION['bio'][$key] = $value;
					}
				}
			}
			if ($_SESSION['bio']['status'] != 1) {
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				return 2;
				exit;
			}
			return 1;
		} else {
			return 3;
		}
	}
	function logout()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function logout2()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function save_user()
	{
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		if (!empty($password))
			$data .= ", password = '" . md5($password) . "' ";
		$data .= ", type = '$type' ";
		if ($type == 1)
			$establishment_id = 0;
		$data .= ", establishment_id = '$establishment_id' ";
		$chk = $this->db->query("Select * from users where username = '$username' and id !='$id' ")->num_rows;
		if ($chk > 0) {
			return 2;
			exit;
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO users set " . $data);
		} else {
			$save = $this->db->query("UPDATE users set " . $data . " where id = " . $id);
		}
		if ($save) {
			return 1;
		}
	}
	function delete_user()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = " . $id);
		if ($delete)
			return 1;
	}
	function signup()
	{
		extract($_POST);
		$data = " name = '" . $firstname . ' ' . $lastname . "' ";
		$data .= ", username = '$email' ";
		$data .= ", password = '" . md5($password) . "' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' ")->num_rows;
		if ($chk > 0) {
			return 2;
			exit;
		}
		$save = $this->db->query("INSERT INTO users set " . $data);
		if ($save) {
			$uid = $this->db->insert_id;
			$data = '';
			foreach ($_POST as $k => $v) {
				if ($k == 'password')
					continue;
				if (empty($data) && !is_numeric($k))
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if ($_FILES['img']['tmp_name'] != '') {
				$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
				$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
				$data .= ", avatar = '$fname' ";
			}
			$save_alumni = $this->db->query("INSERT INTO alumnus_bio set $data ");
			if ($data) {
				$aid = $this->db->insert_id;
				$this->db->query("UPDATE users set alumnus_id = $aid where id = $uid ");
				$login = $this->login2();
				if ($login)
					return 1;
			}
		}
	}
	function update_account()
	{
		extract($_POST);
		$data = " name = '" . $firstname . ' ' . $lastname . "' ";
		$data .= ", username = '$email' ";
		if (!empty($password))
			$data .= ", password = '" . md5($password) . "' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' and id != '{$_SESSION['login_id']}' ")->num_rows;
		if ($chk > 0) {
			return 2;
			exit;
		}
		$save = $this->db->query("UPDATE users set $data where id = '{$_SESSION['login_id']}' ");
		if ($save) {
			$data = '';
			foreach ($_POST as $k => $v) {
				if ($k == 'password')
					continue;
				if (empty($data) && !is_numeric($k))
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if ($_FILES['img']['tmp_name'] != '') {
				$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
				$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
				$data .= ", avatar = '$fname' ";
			}
			$save_alumni = $this->db->query("UPDATE alumnus_bio set $data where id = '{$_SESSION['bio']['id']}' ");
			if ($data) {
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				$login = $this->login2();
				if ($login)
					return 1;
			}
		}
	}

	function save_settings()
	{
		extract($_POST);
		$data = " name = '" . str_replace("'", "&#x2019;", $name) . "' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '" . htmlentities(str_replace("'", "&#x2019;", $about)) . "' ";
		if ($_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", cover_img = '$fname' ";
		}

		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if ($chk->num_rows > 0) {
			$save = $this->db->query("UPDATE system_settings set " . $data);
		} else {
			$save = $this->db->query("INSERT INTO system_settings set " . $data);
		}
		if ($save) {
			$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
			foreach ($query as $key => $value) {
				if (!is_numeric($key))
					$_SESSION['settings'][$key] = $value;
			}

			return 1;
		}
	}


	function save_course()
	{
		extract($_POST);
		$data = " course = '$course' ";
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO courses set $data");
		} else {
			$save = $this->db->query("UPDATE courses set $data where id = $id");
		}
		if ($save)
			return 1;
	}
	function delete_course()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM courses where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	function update_alumni_acc()
	{
		extract($_POST);
		$update = $this->db->query("UPDATE alumnus_bio set status = $status where id = $id");

		if ($update && $status == 1) {
			// Get email
			// Get document's data on database
			$qry = $this->db->query("SELECT * FROM alumnus_bio where id=" . $id)->fetch_array();
			foreach ($qry as $k => $v) {
				$$k = $v;
			}
			try {
				require "../utils/send_email.php";
				$email_subject = "Your account is verified.";
				$email_body = "Congratulations! Your account is successfully verified.";
				send_email($email, $email_subject, $email_body);
			} catch (Exception $e) {
				return $e->getMessage();
			}
		}
		if ($update)
			return 1;
	}
	function save_gallery()
	{
		extract($_POST);
		$img = array();
		$fpath = 'assets/uploads/gallery';
		$files = is_dir($fpath) ? scandir($fpath) : array();
		foreach ($files as $val) {
			if (!in_array($val, array('.', '..'))) {
				$n = explode('_', $val);
				$img[$n[0]] = $val;
			}
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO gallery set name = '$name' ");
			if ($save) {
				$id = $this->db->insert_id;
				$folder = "assets/uploads/gallery/";
				$file = explode('.', $_FILES['img']['name']);
				$file = end($file);
				if (is_file($folder . $id . '/_img' . '.' . $file))
					unlink($folder . $id . '/_img' . '.' . $file);
				if (isset($img[$id]))
					unlink($folder . $img[$id]);
				if ($_FILES['img']['tmp_name'] != '') {
					$fname = $id . '_img' . '.' . $file;
					$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/gallery/' . $fname);
				}
			}
		} else {
			$save = $this->db->query("UPDATE gallery set about = '$about' where id=" . $id);
			if ($save) {
				if ($_FILES['img']['tmp_name'] != '') {
					$folder = "assets/uploads/gallery/";
					$file = explode('.', $_FILES['img']['name']);
					$file = end($file);
					if (is_file($folder . $id . '/_img' . '.' . $file))
						unlink($folder . $id . '/_img' . '.' . $file);
					if (isset($img[$id]))
						unlink($folder . $img[$id]);
					$fname = $id . '_img' . '.' . $file;
					$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/gallery/' . $fname);
				}
			}
		}
		if ($save)
			return 1;
	}
	function delete_gallery()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM gallery where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	function save_career()
	{
		extract($_POST);
		$data = " company = '$company' ";
		$data .= ", job_title = '$title' ";
		$data .= ", location = '$location' ";
		$data .= ", description = '" . htmlentities(str_replace("'", "&#x2019;", $description)) . "' ";

		if (empty($id)) {
			// echo "INSERT INTO careers set ".$data;
			$data .= ", user_id = '{$_SESSION['login_id']}' ";
			$save = $this->db->query("INSERT INTO careers set " . $data);
		} else {
			$save = $this->db->query("UPDATE careers set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function delete_career()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM careers where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	function save_forum()
	{
		extract($_POST);
		$data = " title = '$title' ";
		$data .= ", description = '" . htmlentities(str_replace("'", "&#x2019;", $description)) . "' ";

		if (empty($id)) {
			$data .= ", user_id = '{$_SESSION['login_id']}' ";
			$save = $this->db->query("INSERT INTO forum_topics set " . $data);

			if ($save) {
				// Get the last inserted ID
				$lastInsertedIdQuery = "SELECT LAST_INSERT_ID() as last_id";
				$result = $this->db->query($lastInsertedIdQuery);

				if ($result->num_rows > 0) {
					// Fetch the result and store it in a PHP variable
					$row = $result->fetch_assoc();
					$lastInsertedId = $row['last_id'];

					$this->save_temp_forum($lastInsertedId, $title);
				} else {
					echo "No records found.";
				}
			}
		} else {
			$save = $this->db->query("UPDATE forum_topics set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function save_temp_forum($forum_id, $title)
	{
		$this->db->query("DROP TABLE IF EXISTS temp_forum_topics");

		$this->db->query("CREATE TABLE temp_forum_topics (
			id INT AUTO_INCREMENT PRIMARY KEY,
			title VARCHAR(200),
			forum_id INT
		);");
		$data = " title = '$title' ";
		$data .= ", forum_id = '$forum_id'";
		$this->db->query("INSERT INTO temp_forum_topics set " . $data);
	}
	function delete_forum()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM forum_topics where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	function save_comment()
	{
		extract($_POST);
		include "../utils/badwords.php";
		if (isDataHaveBadWord($comment)) return 2;

		$data = " comment = '" . htmlentities(str_replace("'", "&#x2019;", $comment)) . "' ";

		if (empty($id)) {
			$data .= ", topic_id = '$topic_id' ";
			$data .= ", user_id = '{$_SESSION['login_id']}' ";
			$save = $this->db->query("INSERT INTO forum_comments set " . $data);
		} else {
			$save = $this->db->query("UPDATE forum_comments set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}

	function delete_comment()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM forum_comments where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	function save_event()
	{
		extract($_POST);
		$data = " title = '$title' ";
		$data .= ", schedule = '$schedule' ";
		$data .= ", content = '" . htmlentities(str_replace("'", "&#x2019;", $content)) . "' ";
		if ($_FILES['banner']['tmp_name'] != '') {
			$_FILES['banner']['name'] = str_replace(array("(", ")", " "), '', $_FILES['banner']['name']);
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['banner']['name'];
			$move = move_uploaded_file($_FILES['banner']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", banner = '$fname' ";
		}
		if (empty($id)) {

			$save = $this->db->query("INSERT INTO events set " . $data);
		} else {
			$save = $this->db->query("UPDATE events set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function delete_event()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM events where id = " . $id);
		if ($delete) {
			return 1;
		}
	}

	function participate()
	{
		extract($_POST);
		$data = " event_id = '$event_id' ";
		$data .= ", user_id = '{$_SESSION['login_id']}' ";
		$commit = $this->db->query("INSERT INTO event_commits set $data ");
		if ($commit)
			return 1;
	}

	// Document
	function save_document()
	{
		extract($_POST);
		$document = array();
		$fpath = 'assets/uploads/documents';
		$files = is_dir($fpath) ? scandir($fpath) : array();
		foreach ($files as $val) {
			if (!in_array($val, array('.', '..'))) {
				$n = explode('_', $val);
				$document[$n[0]] = $val;
			}
		}


		$file = explode('.', $_FILES['document']['name']);
		$file = end($file);
		$data = "name = '$name'";
		$data .= ", user_id = '{$_SESSION['login_id']}' ";
		$data .= ", file_extension = '$file'";

		if (empty($id)) {
			$save = $this->db->query("INSERT INTO documents set " . $data);
			if ($save) {
				$id = $this->db->insert_id;
				$folder = "assets/uploads/documents/";
				if (is_file($folder . $id . '/_document' . '.' . $file))
					unlink($folder . $id . '/_document' . '.' . $file);
				if (isset($document[$id]))
					unlink($folder . $document[$id]);
				if ($_FILES['document']['tmp_name'] != '') {
					$fname = $id . '_document' . '.' . $file;
					$move = move_uploaded_file($_FILES['document']['tmp_name'], 'assets/uploads/documents/' . $fname);
				}
			}
		} else {
			$save = $this->db->query("UPDATE documents set " . $data . " where id=" . $id);
			if ($save) {
				if ($_FILES['document']['tmp_name'] != '') {
					$folder = "assets/uploads/documents/";
					$file = explode('.', $_FILES['document']['name']);
					$file = end($file);
					if (is_file($folder . $id . '/_document' . '.' . $file))
						unlink($folder . $id . '/_document' . '.' . $file);
					if (isset($img[$id]))
						unlink($folder . $img[$id]);
					$fname = $id . '_document' . '.' . $file;
					$move = move_uploaded_file($_FILES['document']['tmp_name'], 'assets/uploads/documents/' . $fname);
				}
			}
		}
		if ($save)
			return 1;
	}
	function delete_document()
	{
		extract($_POST);
		$document = array();
		$fpath = 'assets/uploads/documents';
		$files = is_dir($fpath) ? scandir($fpath) : array();
		foreach ($files as $val) {
			if (!in_array($val, array('.', '..'))) {
				$n = explode('_', $val);
				$document[$n[0]] = $val;
			}
		}

		// Get document's data on database
		$qry = $this->db->query("SELECT * FROM documents where id=" . $id)->fetch_array();
		foreach ($qry as $k => $v) {
			$$k = $v;
		}

		// Delete document on folder
		$folder = "assets/uploads/documents/";
		if (is_file($folder . $id . '/_document' . '.' . $file_extension))
			unlink($folder . $id . '/_document' . '.' . $file_extension);
		if (isset($document[$id]))
			unlink($folder . $document[$id]);

		// Delete document on DB
		$delete = $this->db->query("DELETE FROM documents where id = " . $id);
		if ($delete) {
			return 1;
		}
	}

	// Officer
	function save_officer()
	{
		extract($_POST);
		$data = "alumnus_bio_id = '$alumni'";
		$data .= ",position = '$position'";
		try {
			if (empty($id)) {
				$save = $this->db->query("INSERT INTO officers set " . $data);
			} else {
				$save = $this->db->query("UPDATE officers set " . $data . " where id=" . $id);
			}
			if ($save)
				return 1;
		} catch (Exception $e) {
			return 2;
		}
	}

	// Fund
	function save_fund()
	{
		extract($_POST);
		$data = "project_id = '$project_id'";
		$data .= ",current_amount_raised = '$current_amount_raised'";
		$data .= ",target_amount = '$target_amount'";
		$data .= ",fund_manager_id = '$fund_manager_id'";
		try {
			if (empty($id)) {
				$save = $this->db->query("INSERT INTO funds set " . $data);
			} else {
				$save = $this->db->query("UPDATE funds set " . $data . " where id=" . $id);
			}
			if ($save)
				return 1;
		} catch (Exception $e) {
			return 2;
		}
	}
	function delete_fund()
	{
		extract($_POST);
		// Delete document on DB
		$delete = $this->db->query("DELETE FROM funds where id = " . $id);
		if ($delete) {
			return 1;
		}
	}

	// Project

	function save_project()
	{
		extract($_POST);
		$data = "name = '$name'";
		$data .= ",goal = '$goal'";
		$data .= ",start_date = '$start_date'";
		$data .= ",end_date = '$end_date'";
		$data .= ",status = '$status'";
		try {
			if (empty($id)) {
				$save = $this->db->query("INSERT INTO projects set " . $data);
			} else {
				$save = $this->db->query("UPDATE projects set " . $data . " where id=" . $id);
			}
			if ($save)
				return 1;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
	function delete_project()
	{
		extract($_POST);
		// Delete document on DB
		$delete = $this->db->query("DELETE FROM projects where id = " . $id);
		if ($delete) {
			return 1;
		}
	}

	// Notification
	function delete_temp_forum_topics()
	{
		$this->db->query("DROP TABLE IF EXISTS temp_forum_topics");
		return 1;
	}
}
