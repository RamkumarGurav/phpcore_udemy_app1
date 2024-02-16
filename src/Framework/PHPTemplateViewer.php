<?php
declare(strict_types=1);

namespace Framework;

class PHPTemplateViewer implements TemplateViewerInterface
{
    // Method to render a template with data
    public function render($template, array $data = []): string
    {
        // echo "I am inside PHPTemplateViewer's render method with template:$template<br>";

        // Extract variables from the $data array into the current symbol table
        // EXTR_SKIP ensures that if a variable with the same name already exists,
        // it won't be overwritten.
        extract($data, EXTR_SKIP);

        // Start output buffering. This function will turn output buffering on. 
        // While output buffering is active, no output is sent from the script.
        ob_start();

        // Include the template file. The $template variable holds the path to the template file.
        // The "views/" directory is assumed to be in the include path.
        //by requiring this file we save it on output buffer temparrily
        require dirname(__DIR__, 2) . "/views/$template";

        // Get the contents of the output buffer and end output buffering
        // ob_get_clean() fetches the contents of the output buffer and then ends buffering.
        // It returns the buffer contents(if content contains the php code it runs it ) and deletes the output buffer.
        return ob_get_clean();
    }
}
