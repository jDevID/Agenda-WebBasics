<?php
class Toast {
    public static function throwMessage(string $message, string $type = 'info'): void {
        $_SESSION['toast'] = [
            'message' => $message,
            'type' => $type
        ];
    }

    public static function getMessage(): array {
        if (isset($_SESSION['toast'])) {
            $toast = $_SESSION['toast'];
            unset($_SESSION['toast']);
            return $toast;
        }
        return ['message' => '', 'type' => ''];
    }

}
?>


