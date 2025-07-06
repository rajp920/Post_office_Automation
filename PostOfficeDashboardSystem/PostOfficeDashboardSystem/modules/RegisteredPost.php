<?php
if (!class_exists('RegisteredPost')) {
    class RegisteredPost {
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }

        public function registerPost($postData) {
            $trackingNumber = $this->generateTrackingNumber();

            $stmt = $this->conn->prepare("INSERT INTO registered_posts (sender_name, receiver_name, address, tracking_number) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $postData['sender'], $postData['receiver'], $postData['address'], $trackingNumber);

            if ($stmt->execute()) {
                return $trackingNumber;
            }

            return false;
        }

        private function generateTrackingNumber() {
            return 'RP' . strtoupper(uniqid());
        }
    }
}
?>