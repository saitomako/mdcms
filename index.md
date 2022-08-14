# Markdown CMS
## マークダウン記法で、作成するCMS
拡張子`.md`に対して、md.phpを呼び出すように`Action`を設定して、マークダウンベースのCMSを作成した。
基本的な、呼び出し原理は以下の`.htaccess`による。

```
DirectoryIndex index.md index.php index.html
AddType text/markdown md
Action text/markdown /md.php
```

### ディレクトリ構造
```
.
├── Makefile
├── assets
│   ├── css
│   │   ├── fonts
│   │   │   ├── roboto-mono-v6-latin-regular.woff
│   │   │   ├── roboto-mono-v6-latin-regular.woff2
│   │   │   ├── roboto-v19-latin-300italic.woff
│   │   │   ├── roboto-v19-latin-300italic.woff2
│   │   │   ├── roboto-v19-latin-700.woff
│   │   │   ├── roboto-v19-latin-700.woff2
│   │   │   ├── roboto-v19-latin-regular.woff
│   │   │   └── roboto-v19-latin-regular.woff2
│   │   ├── normalize.css
│   │   ├── normalize.css.map
│   │   ├── style.css
│   │   └── style.css.map
│   ├── index.html
│   ├── scss
│   │   ├── _fonts.scss
│   │   ├── _main.scss
│   │   ├── _markdown.scss
│   │   ├── _utils.scss
│   │   ├── _vars.scss
│   │   ├── normalize.scss
│   │   └── style.scss
│   └── templates
│       ├── sample.tpl.html
│       └── scripts.tpl.html
├── book2
│   ├── index.md
│   └── menu.md
├── config.yml
├── example.html
├── index.md
├── lib
│   ├── Michelf
│   │   ├── Markdown.inc.php
│   │   ├── Markdown.php
│   │   ├── MarkdownExtra.inc.php
│   │   ├── MarkdownExtra.php
│   │   ├── MarkdownInterface.inc.php
│   │   └── MarkdownInterface.php
│   ├── MyTemplate.class.php
│   └── spyc
│       ├── COPYING
│       ├── README.md
│       ├── Spyc.php
│       ├── composer.json
│       ├── phpunit.xml
│       └── spyc.yaml
├── markdown-extra.md
├── md.php
└── menu.md
```

## 基本ソース
### md.php
```
<?php
ini_set('display_errors', 'On');
include(__DIR__ . "/lib/MyTemplate.class.php");
include(__DIR__ . "/lib/Michelf/MarkdownExtra.inc.php");
use Michelf\MarkdownExtra;

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
$menufile = dirname($filename) . '/menu.md';
//$tpl->debug = $menufile;
if (!file_exists($menufile)) {
    $menufile = "menu.md";
}
$tpl->navi = MarkdownExtra::defaultTransform(file_get_contents($menufile));
$tpl->show('sample.tpl.html');
```
