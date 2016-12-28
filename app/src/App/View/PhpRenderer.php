<?php

namespace Gallery\App\View;

use Psr\Http\Message\ResponseInterface;

class PhpRenderer extends \Slim\Views\PhpRenderer
{
    protected $layout;

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function render(ResponseInterface $response, $template, array $data = [])
    {
        if ($this->layout) {
            $viewOutput = $this->fetch($template, $data);
            $layoutOutput = $this->fetch($this->layout, ['content' => $viewOutput]);
            $response->getBody()->write($layoutOutput);
        } else {
            $output = parent::render($response, $template, $data);
            $response->getBody()->write($output);
        }

        return $response;
    }

    public function renderPartial(ResponseInterface $response, $template, array $data = [])
    {
        $output = parent::render($response, $template, $data);
        $response->getBody()->write($output);

        return $response;
    }
}
