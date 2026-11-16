<?php
class SessionCheckController
{
    public function checkSession()
    {
        // Validate request method and content type
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid request method or content type']);
            exit;
        }

        session_start();

        if (empty($_SESSION["lang"])) {
            $_SESSION["lang"] = "zh-tw";
        }

        echo json_encode($_SESSION["lang"]);
    }
}

$sessionCheck = new SessionCheckController();
$sessionCheck->checkSession();
