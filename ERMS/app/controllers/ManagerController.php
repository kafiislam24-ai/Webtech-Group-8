<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Request.php';
require_once __DIR__ . '/../models/User.php';

class ManagerController extends Controller
{
    private Request $requestModel;
    private User $userModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Manager') {
            $this->redirect('auth/login');
        }

        $this->requestModel = new Request();
        $this->userModel = new User();
    }

    public function dashboard(): void
    {
        $requests = $this->requestModel->getAllRequests();
        $technicians = $this->userModel->getElementsByRole('Technician');
        
        $this->view('manager/manager_dashboard', [
            'requests' => $requests,
            'technicians' => $technicians
        ]);
    }

    public function assign(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $requestId = $_POST['request_id'] ?? null;
            $techId = $_POST['technician_id'] ?? null;

            if ($requestId && $techId) {
                $this->requestModel->assignTechnician((int)$requestId, (int)$techId);
            }
        }
        $this->redirect('manager/dashboard');
    }
}