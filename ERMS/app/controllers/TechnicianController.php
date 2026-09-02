<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Request.php';

class TechnicianController extends Controller
{
    private Request $requestModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check against $_SESSION['role'] set by AuthController
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Technician') {
            $this->redirect('auth/login');
        }
        $this->requestModel = new Request();
    }

    public function dashboard(): void
    {
        $tasks = $this->requestModel->getByTechnicianId($_SESSION['user_id']);
        $this->view('technician/technician_dashboard', ['tasks' => $tasks]);
    }

    public function updateStatus(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $requestId = $_POST['request_id'] ?? null;
            $status = $_POST['status'] ?? 'In Progress';

            if ($requestId) {
                $this->requestModel->updateStatus((int)$requestId, $status);
            }
        }
        $this->redirect('technician/dashboard');
    }
}