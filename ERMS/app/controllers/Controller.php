<?php

class Controller
{
    public function view(string $viewPath, array $data = []): void
    {
        extract($data);
        $file = __DIR__ . '/../views/' . $viewPath . '.php';

        if (file_exists($file)) {
            require_once $file;
        } else {
            die("MVC Error: View app/views/{$viewPath}.php not found.");
        }
    }

    public function redirect(string $url): void
    {
        header("Location: index.php?url=" . trim($url, '/'));
        exit();
    }
}