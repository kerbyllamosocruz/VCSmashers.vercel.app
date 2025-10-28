<?php
session_start();
require_once "config/config.php"; // Your database connection file

// 1. Security Check: Ensure user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

// 2. Ensure the script is accessed via POST method
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // 3. Get and sanitize input data
    $name = trim($_POST["name"]);
    $phone = trim($_POST["phone"]);
    $userId = $_SESSION["user_id"];

    // 4. Validate the data
    if (empty($name) || empty($phone)) {
        header("Location: profile_page.php?status=error&message=Name and phone cannot be empty.");
        exit();
    }
    
    if (!preg_match('/^[0-9]{11}$/', $phone)) {
        header("Location: profile_page.php?status=error&message=Invalid phone number format.");
        exit();
    }

    // 5. Optional: handle profile picture upload
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
            if ($file['size'] > 2 * 1024 * 1024) { // 2MB
                header("Location: profile_page.php?status=error&message=Image too large (max 2MB).");
                exit();
            }

            $ext = $allowed[$mime];
            if (!is_dir(__DIR__ . '/uploads')) {
                @mkdir(__DIR__ . '/uploads', 0755, true);
            }
            $filename = 'profile_' . $userId . '_' . time() . '.' . $ext;
            $targetAbs = __DIR__ . '/uploads/' . $filename;
            $targetRel = 'uploads/' . $filename; // for browser
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

    // 6. Prepare SQL statement (include profile pic if provided)
    if ($profilePath) {
        $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ?, profile_pic = ? WHERE user_id = ?");
        if ($stmt === false) {
            // Attempt to add missing column then retry once
            if (strpos(strtolower($conn->error), 'unknown column') !== false) {
                @$conn->query("ALTER TABLE users ADD COLUMN profile_pic VARCHAR(255) NULL");
                $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ?, profile_pic = ? WHERE user_id = ?");
            }
        }
        if ($stmt === false) {
            header("Location: profile_page.php?status=error&message=" . urlencode("Database error: " . $conn->error));
            exit();
        }
        $stmt->bind_param("sssi", $name, $phone, $profilePath, $userId);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ? WHERE user_id = ?");
        if ($stmt === false) {
            header("Location: profile_page.php?status=error&message=" . urlencode("Database error: " . $conn->error));
            exit();
        }
        $stmt->bind_param("ssi", $name, $phone, $userId);
    }

    // 6. Execute the update and handle the result
    if ($stmt->execute()) {
        // 7. IMPORTANT: Update the session variables with the new data
        $_SESSION["name"] = $name;
        $_SESSION["phone"] = $phone;
        if ($profilePath) {
            $_SESSION['profile_pic'] = $profilePath;
        }
        
        // Redirect with a success message
        header("Location: profile_page.php?status=success");
        exit();
    } else {
        // Redirect with an error message
        header("Location: profile_page.php?status=error&message=Could not update profile.");
        exit();
    }
    
    $stmt->close();
    $conn->close();

} else {
    // Redirect if accessed directly
    header("Location: profile_page.php");
    exit();
}