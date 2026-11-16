<?php
session_start();

class SessionSetController
{
    public function setSession()
    {
        // Validate request method and content type
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid request method or content type']);
            exit;
        }

        $inputData = !empty($_POST) ? $_POST : json_decode(file_get_contents('php://input'), true);

        if ($inputData === null) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid JSON data']);
            exit;
        }

        $inputId = $inputData["inputId"];

        switch ($inputId) {
            case 'zh-language-btn':
                if ($_SESSION["lang"] === "zh-tw") {
                    $status = "unchanged";
                } else {
                    $_SESSION["lang"] = "zh-tw";
                    $status = "changed";
                }
                break;
            case 'en-language-btn':
                if ($_SESSION["lang"] === "en-us") {
                    $status = "unchanged";
                } else {
                    $_SESSION["lang"] = "en-us";
                    $status = "changed";
                }
                $_SESSION["lang"] = "en-us";
                break;
            default:
                echo json_encode(["status" => "error", "error" => "invalid language selector"]);
                return;
        }

        echo json_encode(["status" => $status]);
    }
}

$sessionSet = new SessionSetController();
$sessionSet->setSession();
