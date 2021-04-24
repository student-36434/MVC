<?php

try {
    spl_autoload_register(function (string $className) {  // загрузка классов  которые ещё не были подключённы
        require_once __DIR__ . '/../src/' . str_replace('\\', '/', $className) . '.php';
    });

    $route = $_GET['route'] ?? '';  // получаем введеный адресс сайта
    $routes = require __DIR__ . '/../src/routes.php'; // подключаем массив с роутингами

    $isRouteFound = false;
    foreach ($routes as $pattern => $controllerAndAction) {
        preg_match($pattern, $route, $matches); // Выполняет проверку на соответствие регулярному выражению
        if (!empty($matches)) { // ищем совпадение по регулярке
            $isRouteFound = true;
            break;
        }
    }

    if (!$isRouteFound) {   // если не нашли бросаем исключение
        throw new \MyProject\Exceptions\NotFoundException();
    }

    unset($matches[0]); // удаляем полное совпадение по регулярке, оставля только маску

    $controllerName = $controllerAndAction[0]; // Имя контроллера
    $actionName = $controllerAndAction[1];     // Имя метода

    $controller = new $controllerName();      // создаем контроллер
    $controller->$actionName(...$matches);    // вызываем нужный метод. Оператор ... передаст элементы массива в качестве аргументов методу
} catch (\MyProject\Exceptions\DbException $e) {
    $view = new \MyProject\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('500.php', ['error' => $e->getMessage()], 500);
} catch (\MyProject\Exceptions\NotFoundException $e) {
    $view = new \MyProject\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('404.php', ['error' => $e->getMessage()], 404);
} catch (\MyProject\Exceptions\UnauthorizedException $e) {
    $view = new \MyProject\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('401.php', ['error' => $e->getMessage()], 401);
} catch (\MyProject\Exceptions\ForbiddenException $e) {
        $view = new \MyProject\View\View(__DIR__ . '/../templates/errors');
        $view->renderHtml('403.php', ['error' => $e->getMessage()], 403);
}
