<?php
namespace FlavorHub\BusinessLogic;

use FlavorHub\DataAccess\UserDAO;

/**
 * Authentication Service (Business Logic Layer)
 * Manages user sessions, login/logout, and security validations.
 */
class AuthService {
    private UserDAO $userDAO;

    public function __construct(UserDAO $userDAO) {
        $this->userDAO = $userDAO;
    }

    /**
     * Safe session starter.
     */
    public static function startSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            // Set secure session cookie parameters
            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'domain' => '',
                'secure' => false, // Set to true if using HTTPS
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
            session_start();
        }
    }

    /**
     * Authenticate user credentials and start session.
     */
    public function login(string $email, string $password): bool {
        self::startSession();
        
        $user = $this->userDAO->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            // Prevent Session Fixation
            session_regenerate_id(true);

            $_SESSION['user_id']       = $user['id'];
            $_SESSION['fullname']      = $user['fullname'];
            $_SESSION['email']         = $user['email'];
            $_SESSION['last_activity'] = time();
            
            return true;
        }
        return false;
    }

    /**
     * Check if user is authenticated, handle session expiration (30 mins).
     */
    public function checkAuth(): void {
        self::startSession();
        
        if (!isset($_SESSION['user_id'])) {
            // Redirect to root login page
            header('Location: ../index.php');
            exit;
        }

        // Timeout check (1800 seconds = 30 minutes)
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
            $this->logout();
            header('Location: ../index.php?error=Session+expired.+Please+login+again.');
            exit;
        }
        
        // Refresh activity timestamp
        $_SESSION['last_activity'] = time();
    }

    /**
     * Clear session cookies and destroy current session.
     */
    public function logout(): void {
        self::startSession();
        
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), 
                '', 
                time() - 42000,
                $params["path"], 
                $params["domain"],
                $params["secure"], 
                $params["httponly"]
            );
        }

        session_destroy();
    }
}
