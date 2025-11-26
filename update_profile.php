<?php
session_start();
require_once "config/config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone']);
    $userId = $_SESSION['user_id'];

    if (empty($phone)) {
        header("Location: profile_page.php?status=error&message=Phone number cannot be empty.");
        exit();
    }

    if (!preg_match('/^[0-9]{11}$/', $phone)) {
        header("Location: profile_page.php?status=error&message=Invalid phone number format.");
        exit();
    }

    $profilePath = null;
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['profile_pic'];
        if ($file['error'] === UPLOAD_ERR_OK) {
            $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!isset($allowed[$mime])) {
                header("Location: profile_page.php?status=error&message=Invalid image type.");
                exit();
            }
            if ($file['size'] > 2 * 1024 * 1024) {
                header("Location: profile_page.php?status=error&message=Image too large (max 2MB).");
                exit();
            }

            $ext = $allowed[$mime];
            if (!is_dir(__DIR__ . '/uploads')) {
                @mkdir(__DIR__ . '/uploads', 0755, true);
            }
            $filename = 'profile_' . $userId . '_' . time() . '.' . $ext;
            $targetAbs = __DIR__ . '/uploads/' . $filename;
            $targetRel = 'uploads/' . $filename;
            if (!move_uploaded_file($file['tmp_name'], $targetAbs)) {
                header("Location: profile_page.php?status=error&message=Failed to upload image.");
                exit();
            }
            $profilePath = $targetRel;
        } else {
            header("Location: profile_page.php?status=error&message=Upload error.");
            exit();
        }
    }

    if ($profilePath) {
        $stmt = $conn->prepare("UPDATE users SET phone = ?, profile_pic = ? WHERE user_id = ?");
        if ($stmt === false) {
            if (strpos(strtolower($conn->error), 'unknown column') !== false) {
                @$conn->query("ALTER TABLE users ADD COLUMN profile_pic VARCHAR(255) NULL");
                $stmt = $conn->prepare("UPDATE users SET phone = ?, profile_pic = ? WHERE user_id = ?");
            }
        }
        if ($stmt === false) {
            header("Location: profile_page.php?status=error&message=" . urlencode("Database error: " . $conn->error));
            exit();
        }
        $stmt->bind_param("ssi", $phone, $profilePath, $userId);
    } else {
        $stmt = $conn->prepare("UPDATE users SET phone = ? WHERE user_id = ?");
        if ($stmt === false) {
            header("Location: profile_page.php?status=error&message=" . urlencode("Database error: " . $conn->error));
            exit();
        }
        $stmt->bind_param("si", $phone, $userId);
    }

    if ($stmt->execute()) {
        $_SESSION['phone'] = $phone;
        if ($profilePath) {
            $_SESSION['profile_pic'] = $profilePath;
        }

        header("Location: profile_page.php?status=success");
        exit();
    } else {
        header("Location: profile_page.php?status=error&message=Could not update profile.");
        exit();
    }

    $stmt->close();
    $conn->close();

} else {
    header("Location: profile_page.php");
    exit();
}