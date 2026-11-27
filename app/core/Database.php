<?php
class Database
{
    private static $instance = null;
    private $conn;

    private function __construct()
    {
        $localConfig = __DIR__ . '/database.local.php';
        $settings = is_file($localConfig) ? require $localConfig : [];
        foreach (['DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASSWORD'] as $key) {
            $value = getenv($key);
            if ($value !== false) {
                $settings[$key] = $value;
            }
        }

        foreach (['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASSWORD'] as $key) {
            if (!is_string($settings[$key] ?? null) || ($key !== 'DB_PASSWORD' && $settings[$key] === '')) {
                throw new RuntimeException("Missing database setting: $key");
            }
        }
        $port = filter_var($settings['DB_PORT'] ?? '3306', FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 65535],
        ]);
        if ($port === false) {
            throw new RuntimeException('Invalid database setting: DB_PORT');
        }

        $this->conn = new PDO(
            "mysql:host={$settings['DB_HOST']};port=$port;dbname={$settings['DB_NAME']};charset=utf8mb4",
            $settings['DB_USER'],
            $settings['DB_PASSWORD'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    public static function getInstance()
    {
        if(self::$instance === null)
        {
            self::$instance = new Database();
        }

        return self::$instance->conn;
    }
}
