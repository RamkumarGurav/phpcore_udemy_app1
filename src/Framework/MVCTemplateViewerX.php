<?php
declare(strict_types=1);

namespace Framework;

class MVCTemplateViewerX implements TemplateViewerInterface
{

  public string $code;
  public string $layoutCode;
  public array $sections = [];

  public function addSection(string $sectionName, string $section)
  {
    $this->sections[$sectionName] = $section;
  }

  public function setCode(string $code)
  {
    $this->code = $code;
  }

  public function getCode(): string
  {
    return $this->code;
  }
  public function setLayoutCode(string $code)
  {
    $this->layoutCode = $code;
  }

  public function getlayoutCode(): string
  {
    return $this->layoutCode;
  }



  public function render(string $template, array $data = []): string
  {

    // Extract variables from the $data array into the current symbol table
    // EXTR_SKIP ensures that if a variable with the same name already exists,
    // it won't be overwritten.
    $views_dir = dirname(__DIR__, 2) . "/views/";
    $this->setCode(file_get_contents($views_dir . $template));
    extract($data, EXTR_SKIP);

    ob_start();
    eval("?> {$this->code}");
    $code = "'".ob_get_clean().".";
    $this->getSectionsFromCode($code);

    echo $code;
    // print_r($this->sections);
    exit;
    print_r($this->sections);
    exit;
    ob_start();
    eval("?> {$this->layoutCode}");
    $code = ob_get_clean();
    ;


  }


  public function evalCode(string $code): string
  {

    ob_start();
    eval("?> $code");
    $code = ob_get_clean();
    return $code;
  }






  private function getSectionsFromCode(string $code): void
  {

    preg_match_all("#{% section (?<sectionname>\w+) %}(?<content>.*?){% endSection %}#s", $code, $matches, PREG_SET_ORDER);

    exit($code);
    $sections = [];
    foreach ($matches as $match) {

      $sections[$match["sectionname"]] = $match["content"];
    }

    print_r(array_keys($sections));
    exit;
  }




  public function include(string $dir)
  {

    $views_dir = dirname(__DIR__, 2) . "/views/";

    $contentFromTemplate = file_get_contents($views_dir . $dir);



    echo $this->evalCode($contentFromTemplate);
  }

  public function extend(string $dir)
  {

    $views_dir = dirname(__DIR__, 2) . "/views/";

    $layoutCode = file_get_contents($views_dir . $dir);

    $layoutCode = $this->evalCode($layoutCode);
    $this->setLayoutCode($layoutCode);
    echo $this->layoutCode;



  }


  public function section(string $name)
  {
    echo "{% section $name %}";
  }

  public function endSection()
  {
    echo "{% endSection %}";
  }


  public function renderSection(string $name)
  {
    echo "<?= \$this->sections[\"$name\"] ?>";


  }

}

