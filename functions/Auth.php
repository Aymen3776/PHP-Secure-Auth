<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "./config/database.php";
function generateCsrfToken()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}
function validateCsrfToken($token)
{
    if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
        unset($_SESSION['csrf_token']);
        return true;
    }
    return false;
}
function isLoggedIn()
{
    global $pdo;
    if (isset($_SESSION['user_id'])) {
        return true;
    }

    if (isset($_COOKIE['remember_me'])) {
        list($selector, $validator) = explode(':', $_COOKIE['remember_me']);

        if ($selector && $validator) {
            $sql = "SELECT * FROM auth_tokens WHERE selector = :selector AND expires_at >= NOW()";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['selector' => $selector]);
            $token = $stmt->fetch();

            if ($token) {
                $hashedValidator = hash('sha256', $validator);

                if (hash_equals($token['hashed_validator'], $hashedValidator)) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $token['user_id'];

                    return true;
                }
            }
        }
    }
    return false;
}
function loginUser($email, $password, $rememberMe = false)
{
    global $pdo;

    if (isset($_SESSION['login_cooldown_until']) && time() < $_SESSION['login_cooldown_until']) {
        $remaining = $_SESSION['login_cooldown_until'] - time();
        return ['success' => false, 'message' => "Çok fazla deneme yaptınız. Lütfen {$remaining} saniye sonra tekrar deneyin."];
    }

    $sql = "SELECT id, username, password_hash FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();
    $passwordHash = $user ? $user['password_hash'] : '$2y$10$dummydummydummydummydummy.dummydummy';

    if (password_verify($password, $passwordHash) && $user) {
        unset($_SESSION['login_attempts']);
        unset($_SESSION['login_cooldown_until']);

        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        if ($rememberMe) {
            $selector = bin2hex(random_bytes(16));
            $validator = bin2hex(random_bytes(32));
            $hashedValidator = hash('sha256', $validator);
            $expires = new DateTime('+30 days');

            $sql_insert = "INSERT INTO auth_tokens (user_id, selector, hashed_validator, expires_at) VALUES (:user_id, :selector, :hashed_validator, :expires_at)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->execute([
                'user_id' => $user['id'],
                'selector' => $selector,
                'hashed_validator' => $hashedValidator,
                'expires_at' => $expires->format('Y-m-d H:i:s')
            ]);
            $isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
            setcookie('remember_me', $selector . ':' . $validator, $expires->getTimestamp(), '/', '', $isSecure, true);
        }

        return ['success' => true, 'message' => 'Giriş başarılı!'];
    } else {
        $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
        if ($_SESSION['login_attempts'] >= 5) {
            $_SESSION['login_cooldown_until'] = time() + (5 * 60);
            unset($_SESSION['login_attempts']);
        }
        return ['success' => false, 'message' => 'E-posta veya şifre hatalı.'];
    }
}
function registerUser($username, $email, $password)
{
    global $pdo;
    if (strlen($username) < 3 || strlen($username) > 50) {
        return ['success' => false, 'message' => 'Kullanıcı adı 3-50 karakter arasında olmalıdır.'];
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Lütfen geçerli bir e-posta adresi girin.'];
    }
    if (strlen($password) < 8) {
        return ['success' => false, 'message' => 'Şifre en az 8 karakter uzunluğunda olmalıdır.'];
    }

    $sql = "SELECT id FROM users WHERE username = :username OR email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['username' => $username, 'email' => $email]);
    if ($stmt->fetch()) {
        return ['success' => false, 'message' => 'Bu e-posta veya kullanıcı adı zaten kullanılıyor.'];
    }
    $passwordHash = password_hash($password, PASSWORD_ARGON2ID);
    $sql = "INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute(['username' => trim($username), 'email' => $email, 'password_hash' => $passwordHash]);

    if ($result) {
        return ['success' => true, 'message' => 'Kayıt başarılı! Şimdi giriş yapabilirsiniz.'];
    }

    return ['success' => false, 'message' => 'Kayıt sırasında bir hata oluştu.'];
}
function logOut()
{
    global $pdo;

    if (isset($_COOKIE['remember_me'])) {
        list($selector,) = explode(':', $_COOKIE['remember_me']);

        $sql = "DELETE FROM auth_tokens WHERE selector = :selector";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['selector' => $selector]);

        setcookie('remember_me', '', time() - 3600, '/');
    }

    $_SESSION = [];
    session_destroy();
}
function getUserById($userId)
{
    global $pdo;

    $sql = "SELECT id, username, email, created_at FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user;
}
function turkceTarih($tarih)
{
    $aylar = [
        'January' => 'Ocak',
        'February' => 'Şubat',
        'March' => 'Mart',
        'April' => 'Nisan',
        'May' => 'Mayıs',
        'June' => 'Haziran',
        'July' => 'Temmuz',
        'August' => 'Ağustos',
        'September' => 'Eylül',
        'October' => 'Ekim',
        'November' => 'Kasım',
        'December' => 'Aralık',
    ];

    $tarihFormatli = date('d F Y', strtotime($tarih));
    return strtr($tarihFormatli, $aylar);
}
