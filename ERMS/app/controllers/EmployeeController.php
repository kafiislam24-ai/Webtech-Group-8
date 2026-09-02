<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Request.php';

class EmployeeController extends Controller
{
    private Request $requestModel;

    public function __construct()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Employee') {
            $this->redirect('auth/login');
        }
        $this->requestModel = new Request();
    }

    public function dashboard(): void
    {
        $requests = $this->requestModel->getByUserId($_SESSION['user_id']);
        $this->view('employee/employee_dashboard', ['requests' => $requests]);
    }

    public function createRequest(): void
    {
        $this->view('employee/request_form');
    }

    public function storeRequest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $equipment = trim($_POST['equipment_name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if (!empty($equipment) && !empty($description)) {
                $this->requestModel->create([
                    'user_id' => $_SESSION['user_id'],
                    'equipment_name' => $equipment,
                    'description' => $description,
                    'status' => 'Pending'
                ]);
            }
        }
        $this->redirect('employee/dashboard');
    }
}