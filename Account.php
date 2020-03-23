<?php

namespace application\models;

use application\core\Model;

class Account extends Model {

		// Валидность, авторизация, регистрация, смена пароля.	
	
		public function validate($input, $post) {
			$rules = [
				'email' => [
					'pattern' => '#^([a-z0-9_.-]{1,20}+)@([a-z0-9_.-]+)\.([a-z\.]{2,10})$#',
					'message' => 'E-mail адрес указан неверно',
				],
				'login' => [
					'pattern' => '#^[a-z0-9]{3,15}$#',
					'message' => 'Логин указан неверно (разрешены только латинские буквы и цифры от 3 до 15 символов',
				],
				'password' => [
					'pattern' => '#^[a-z0-9]{6,10}$#',
					'message' => 'Пароль указан неверно (разрешены только латинские буквы и цифры от 6 до 10 символов',
				],
			];
			foreach ($input as $val) {
				if (!isset($post[$val]) or !preg_match($rules[$val]['pattern'], $post[$val])) {
					$this->error = $rules[$val]['message'];
					return false;
				}
			}
			return true;
		}

		public function checkEmailExists($email) {
			$params = [
				'email' => $email,
			];
			return $this->db->column('SELECT id FROM accounts WHERE email = :email', $params);
		}

		public function checkLoginExists($login) {
			$params = [
				'login' => $login,
			];
			if ($this->db->column('SELECT id FROM accounts WHERE login = :login', $params)) {
				$this->error = 'Этот логин уже используется';
				return false;
			}
			return true;
		}

		public function checkTokenExists($token) {
			$params = [
				'token' => $token,
			];
			return $this->db->column('SELECT id FROM accounts WHERE token = :token', $params);
		}

		public function createToken() {
			return substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyz', 30)), 0, 30);
		}

		public function register($post) {
			$token = $this->createToken();
			$params = [
				'id' => '',
				'email' => $post['email'],
				'login' => $post['login'],
				'password' => password_hash($post['password'], PASSWORD_BCRYPT),
				'token' => $token,
			];
			$this->db->query('INSERT INTO accounts VALUES (:id, :email, :login, :password, :token)', $params);
		}

		public function checkData($login, $password) {
			$params = [
				'login' => $login,
			];
			$hash = $this->db->column('SELECT password FROM accounts WHERE login = :login', $params);
			if (!$hash or !password_verify($password, $hash)) {
				return false;
			}
			return true;
		}

		public function login($login) {
			$params = [
				'login' => $login,
			];
			$data = $this->db->row('SELECT * FROM accounts WHERE login = :login', $params);
			$_SESSION['account'] = $data[0];
		}

		public function recovery($post) {
			$token = $this->createToken();
			$params = [
				'email' => $post['email'],
			];
			$this->db->query('UPDATE accounts WHERE email = :email', $params);
		}

		public function reset($token) {
			$new_password = $this->createToken();
			$params = [
				'token' => $token,
				'password' => password_hash($new_password, PASSWORD_BCRYPT),
			];
			$this->db->query('UPDATE accounts SET token = "", password = :password WHERE token = :token', $params);
			return $new_password;
		}

		public function save($post) {
			$params = [
				'id' => $_SESSION['account']['id'],
				'email' => $post['email'],
			];
			if (!empty($post['password'])) {
				$params['password'] = password_hash($post['password'], PASSWORD_BCRYPT);
				$sql = ',password = :password';
			}
			else {
				$sql = '';
			}
			foreach ($params as $key => $val) {
				$_SESSION['account'][$key] = $val;
			}
			$this->db->query('UPDATE accounts SET email = :email'.$sql.' WHERE id = :id', $params);
		}

		// Основное для работы Note.

		public function noteValidate($post, $type) {
			$nameLen = iconv_strlen($post['name']);
			$descriptionLen = iconv_strlen($post['description']);
			$textLen = iconv_strlen($post['text']);
			if ($nameLen < 3 or $nameLen > 100) {
				$this->error = 'Название должно содержать от 3 до 100 символов';
				return false;
			} elseif ($descriptionLen < 3 or $descriptionLen > 100) {
				$this->error = 'Описание должно содержать от 3 до 100 символов';
				return false;
			} elseif ($textLen < 10 or $textLen > 5000) {
				$this->error = 'Текст должнен содержать от 10 до 5000 символов';
				return false;
			}
			return true;
		}

		public function noteAdd($post) {
			$unixTime = date();
			$params = [
				'unixTime' => $unixTime,
				'id' => '',
				'name' => $post['name'],
				'description' => $post['description'],
				'text' => $post['text'],
			];
			$this->db->query('INSERT INTO notes VALUES (:id, :name, :unixTime,:description, :text)', $params);
		}

		public function noteCount() {
			return $this->db->column('SELECT COUNT(id) FROM notes');
		}

		public function noteList($route) {
			//
		}

		public function noteEdit($post, $id) {
			$params = [
				'id' => $id,
				'name' => $post['name'],
				'description' => $post['description'],
				'text' => $post['text'],
			];
			$this->db->query('UPDATE notes SET name = :name, description = :description, text = :text WHERE id = :id', $params);
		}

}

?>
