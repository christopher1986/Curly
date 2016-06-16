<?php 

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Curly\Engine;
use Curly\TemplateContext;

$input  = file_get_contents('file.txt'); 
$engine = new Engine();

$template = $engine->loadTemplate($input);
$context  = new TemplateContext(array(
    'months' => array('januari', 'februari', 'maart')
));

echo $template->render($context);



