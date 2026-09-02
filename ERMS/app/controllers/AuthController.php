<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/User.php';

class AuthController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function login(): void
    {
        if (isset($_SESSION['role'])) {
            $role = strtolower($_SESSION['role']);
            $this->redirect("{$role}/dashboard");
        }
        $this->view('auth/login');
    }

    public function processLogin(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $this->redirect('auth/login');
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = trim($_POST['role'] ?? '');

        $user = $this->userModel->login($email, $password, $role);

        if ($user) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];

            $dashboard = strtolower($user['role']);
            $this->redirect("{$dashboard}/dashboard");
        } else {
            $this->view('auth/login', ['errors' => ['Invalid email, password, or role choice.']]);
        }
    }

    public function register(): void
    {
        $this->view('auth/register');
    }

    public function processRegister(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $this->redirect('auth/register');
        }

        $fullname = trim($_POST['fullname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $role = trim($_POST['role'] ?? '');

        if ($password !== $confirmPassword) {
            $this->view('auth/register', ['errors' => ['Passwords do not match.']]);
            return;
        }

        $registered = $this->userModel->register($fullname, $email, $password, $role);

        if ($registered) {
            $this->view('auth/login', ['success' => 'Registration successful! You may log in now.']);
        } else {
            $this->view('auth/register', ['errors' => ['Email is already registered.']]);
        }
    }
}