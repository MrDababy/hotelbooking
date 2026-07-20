<?php
if (!class_exists('Database')) {
    class Database
    {
        private $host = "localhost";
        private $dbname = "hotel_booking";
        private $username = "booking_user";
        private $password = "12345";

        public $conn;

        public function connect()
        {
            $this->conn = null;

            try {
                $this->conn = new PDO(
                    "mysql:host={$this->host};dbname={$this->dbname}",
                    $this->username,
                    $this->password
                );

                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection Failed: " . $e->getMessage());
            }

            return $this->conn;
        }
    }
}
?>