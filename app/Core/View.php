<?php

namespace WpBoilerplate\Core;

/**
 * View class
 */
class View
{
    /**
     * Render a view
     *
     * @param string $view View name
     * @param array $data Data to pass to the view
     * @param bool $return Whether to return the view or echo it
     * @return string|void
     */
    public function render($view, $data = [], $return = false)
    {
        $file = WP_BOILERPLATE_DIR . 'app/Views/' . $view . '.php';

        if (!file_exists($file)) {
            return '';
        }

        extract($data);

        ob_start();
        include $file;
        $content = ob_get_clean();

        if ($return) {
            return $content;
        }

        echo $content;
    }
}
