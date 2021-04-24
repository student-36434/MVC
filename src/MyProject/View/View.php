<?php

namespace MyProject\View;

class View
{
    private $templatesPath;

    private $extraVars = [];

    public function __construct(string $templatesPath)
    {
        $this->templatesPath = $templatesPath;   // путь до папки с шаблонами
    }

    public function setVar(string $name, $value): void // добавлять переменные еще перед рендерингом
    {
        $this->extraVars[$name] = $value;
    }

    public function renderHtml(string $templateName, array $vars = [], int $code = 200) // подключение шаблона
    {
        http_response_code($code); // Задать код ответа

        extract($this->extraVars);
        extract($vars);  // извлекает массив в переменные
        //временный буфер вывода
        ob_start(); //  Включение буферизации вывода
        include $this->templatesPath . '/' . $templateName;
        $buffer = ob_get_contents();  // Возвращает содержимое буфера вывода
        ob_end_clean(); //Очистить буфер вывода и отключить буферизацию вывода

        echo $buffer;
    }
}
