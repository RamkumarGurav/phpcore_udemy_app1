<?php
declare(strict_types=1);

namespace Framework;

class MVCTemplateViewer implements TemplateViewerInterface
{
  // Method to render a template with data
  public function render($template, array $data = []): string
  {
    // echo "I am inside MVCTemplateViewer's render method with template:$template<br>";

    $views_dir = dirname(__DIR__, 2) . "/views/";

    $code = file_get_contents($views_dir . $template);

    if (preg_match('#{% extend "(?<template>.*)" %}#', $code, $matches) === 1) {

      $layoutCode = file_get_contents($views_dir . $matches["template"]);
      $sections = $this->getSections($code);
      $code = $this->replaceSections($layoutCode, $sections);
    }

    $code = $this->loadIncludes($views_dir, $code);
    $code = $this->replaceVariables($code);
    $code = $this->replacePHP($code);

    // exit($code);

    // Extract variables from the $data array into the current symbol table
    // EXTR_SKIP ensures that if a variable with the same name already exists,
    // it won't be overwritten.
    extract($data, EXTR_SKIP);

    // Start output buffering. This function will turn output buffering on. 
    // While output buffering is active, no output is sent from the script.
    ob_start();

    eval("?> $code");

    // Get the contents of the output buffer and end output buffering
    // ob_get_clean() fetches the contents of the output buffer and then ends buffering.
    // It returns the buffer contents(if content contains the php code it runs it ) and deletes the output buffer.
    return ob_get_clean();

  }



  private function replaceVariables(string $code): string
  {


    return preg_replace("#{{\s*(\S+)\s*}}#", "<?= htmlspecialchars(\$$1 ?? '') ?>", $code);

  }


  private function replacePHP(string $code): string
  {
    return preg_replace("#{%\s*(.+)\s*%}#", "<?php $1 ?>", $code);
  }


  private function getSections(string $code): array
  {

    preg_match_all("#{% section (?<sectionname>\w+) %}(?<content>.*?){% endSection %}#s", $code, $matches, PREG_SET_ORDER);

    $sections = [];
    foreach ($matches as $match) {

      $sections[$match["sectionname"]] = $match["content"];
    }
    return $sections;

  }



  private function replaceSections(string $layoutCode, array $sections): string
  {

    preg_match_all("#{% renderSection (?<sectionname>\w+) %}#", $layoutCode, $matches, PREG_SET_ORDER);

    foreach ($matches as $match) {

      $sectionname = $match["sectionname"];
      $section = $sections[$sectionname];

      $layoutCode = preg_replace("#{% renderSection $sectionname %}#", $section, $layoutCode);

    }

    return $layoutCode;
  }


  private function loadIncludes(string $dir, string $code): string
  {
    preg_match_all('#{% include "(?<template>.*?)" %}#', $code, $matches, PREG_SET_ORDER);

    foreach ($matches as $match) {

      $template = $match["template"];
      $contentFromTemplate = file_get_contents($dir . $template);
      $code = preg_replace("#{% include \"$template\" %}#", $contentFromTemplate, $code);

    }

    return $code;
  }




}




//{--------------EXPLANATION--------------
  
// This PHP class, MVCTemplateViewer, implements the TemplateViewerInterface within the Framework namespace. It provides functionality to render templates with data.

// Here's a brief explanation of its main features:

// Rendering Templates:

// The render method takes a template name and optional data array, reads the template file, and processes it.
// If the template extends another template, it replaces the {% extend %} tag with the content of the parent template.
// It also replaces {% include %} tags with the content of the included templates.
// PHP code within {% %} tags is evaluated using eval().
// Variable Replacement:

// It replaces {{ }} tags with PHP code that echoes the corresponding variable, ensuring proper escaping with htmlspecialchars().
// Section Rendering:

// It supports defining sections within templates using {% section %} and {% endSection %} tags.
// Sections can be rendered within a layout template using {% renderSection %} tags.
// PHP Code Parsing:

// It replaces {% %} tags with standard PHP opening and closing tags
// Overall, this class facilitates the processing and rendering of templates, including handling layout inheritance, including other templates, and rendering sections.