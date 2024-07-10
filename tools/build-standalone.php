<?php
$out = fopen('Tyme.php', 'w') or die('Unable to open Tyme.php.');
fwrite($out, '<?php'."\r\n\r\n");

class ClassInfo
{
    public string $path;
    public array $namespaces;
    public array $uses;
}

function writeClass($path): void
{
    global $out;
    $isClass = false;
    $lines = file($path);
    foreach ($lines as $line) {
        if (!$isClass) {
            if (str_contains($line, '/') || str_contains($line, 'class') || str_contains($line, 'bcscale')) {
                $isClass = true;
            }
        }
        if ($isClass) {
            fwrite($out, $line);
        }
    }
    fwrite($out, "\r\n");
}

function parseFile($path): ClassInfo
{
    $isClass = false;
    $namespaces = array();
    $uses = array();
    $lines = file($path);
    foreach ($lines as $line) {
        if (!$isClass) {
            if (str_contains($line, 'namespace ')) {
                if (!in_array($line, $namespaces)) {
                    $namespaces[] = $line;
                }
            } else if (str_contains($line, 'use ')) {
                if (!in_array($line, $uses)) {
                    $uses[] = $line;
                }
            } else if (str_contains($line, '/') || str_contains($line, 'class') || str_contains($line, 'bcscale')) {
                $isClass = true;
            }
        }
    }

    $classInfo = new ClassInfo();
    $classInfo->path = $path;
    $classInfo->namespaces = $namespaces;
    $classInfo->uses = $uses;
    return $classInfo;
}

function parseDirectory($path): void
{
    global $out;
    $namespaces = array();
    $uses = array();

    $files = glob(rtrim($path, '/') . '/*');
    if ('../src' == $path) {
        usort($files, function ($a, $b) {
            $sorts = array();
            foreach (['ExtendTrait', 'Culture', 'Tyme', 'AbstractCulture', 'AbstractCultureDay', 'AbstractTyme', 'LoopTyme'] as $name) {
                $sorts[] = '../src/'. $name . '.php';
            }
            return array_search($a, $sorts) - array_search($b, $sorts);
        });
    }
    foreach ($files as $file) {
        if (is_file($file)) {
            $classInfo = parseFile($file);
            foreach ($classInfo->namespaces as $line) {
                if (!in_array($line, $namespaces)) {
                    $namespaces[] = $line;
                }
            }
            foreach ($classInfo->uses as $line) {
                if (!in_array($line, $uses)) {
                    $uses[] = $line;
                }
            }
        }
    }

    if (count($namespaces) > 0) {
        foreach ($namespaces as $line) {
            fwrite($out, $line);
        }
        fwrite($out, "\r\n\r\n");
    }

    if (count($uses) > 0) {
        foreach ($uses as $line) {
            fwrite($out, $line);
        }
        fwrite($out, "\r\n");
    }

    foreach ($files as $file) {
        if (is_file($file)) {
            writeClass($file);
        }
    }

    foreach ($files as $file) {
        if (is_dir($file)) {
            parseDirectory($file);
        }
    }
}

parseDirectory('../src');

echo('Done!');
