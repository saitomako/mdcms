<?php
ini_set('display_errors', 'On');
include(__DIR__ . "/lib/MyTemplate.class.php");
include(__DIR__ . "/lib/Michelf/MarkdownExtra.inc.php");
use Michelf\MarkdownExtra;

function get_menufile($filename) {
    $menufile = dirname($filename) . '/menu.md';
    if (!file_exists($menufile)) {
        return get_menufile(dirname($menufile));
    } else {
        return $menufile;
    }
}

$tpl = new MyTemplate("./config.yml");
$tpl->sitename = $tpl->conf["sitename"];

$filename = "";
if (isset($_SERVER['PATH_TRANSLATED'])) {
    $filename = realpath($_SERVER['PATH_TRANSLATED']);
}
$tpl->var = $filename;
$text = file_get_contents($filename);
$lines = explode("\n", $text);
if (substr($lines[0], 0, 2) == "# ") {
    $tpl->title = substr($lines[0], 2) . " | " . $tpl->sitename;
}

$tpl->content = MarkdownExtra::defaultTransform($text);
$menufile = get_menufile($filename);
//$tpl->debug = $menufile;
$tpl->navi = MarkdownExtra::defaultTransform(file_get_contents($menufile));
$tpl->show('sample.tpl.html');
