<?php
class View {
    public static function render($view, $data = []) {
        $pageTitle = $data['pageTitle'] ?? 'Phoenix Pizza';
        extract($data);

        include "../views/partials/header.php";
        include "../views/{$view}.php";
        include "../views/partials/footer.php";
    }
}
