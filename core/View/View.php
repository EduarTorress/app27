<?php

namespace Core\View;

class View
{

    protected string $layout = '';
    protected array $sections = [];

    public function setLayout(string $layout)
    {
        $this->layout = $layout;
    }

    public function render(string $viewFile, array $parameters = [])
    {
        $contentView = $this->getContentView($viewFile, $parameters);
        if ($this->layout !== '') {
            $contentView = $this->getContentLayout();
        }
        return $contentView;
    }

    public function startSection(string $section)
    {
        $this->sections[$section] = '';
        ob_start();
    }

    public function endSection(string $section)
    {
        $this->sections[$section] = ob_get_clean();
    }

    protected function section(string $section, $default = '')
    {
        return $this->sections[$section] ?? $default;
    }

    protected function getContentLayout()
    {
        ob_start();
        require __DIR__ . "/../../views/" . $this->layout . ".php";
        return ob_get_clean();
    }

    protected function getContentView(string $viewFile, array $parameters)
    {
        extract($parameters);
        ob_start();
        require __DIR__ . "/../../views/" . $viewFile . ".php";
        return ob_get_clean();
    }
}
