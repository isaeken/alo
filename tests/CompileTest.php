<?php

namespace IsaEken\Alo\Test;

use Illuminate\Support\Str;
use IsaEken\Alo\Alo;
use PHPUnit\Framework\TestCase;

class CompileTest extends TestCase
{
    public function testInclude()
    {
        $alo = new Alo;
        $alo->project_path = __DIR__ . DIRECTORY_SEPARATOR . "tests";
        $alo->main_file = $alo->project_path . DIRECTORY_SEPARATOR . "include.php";
        $alo->output = $alo->project_path . DIRECTORY_SEPARATOR . "include.php.result";
        $alo->run();
        $this->assertStringContainsString("Lorem ipsum dolor sit amet", file_get_contents($alo->output));
    }

    public function testIncludeOnce()
    {
        $alo = new Alo;
        $alo->project_path = __DIR__ . DIRECTORY_SEPARATOR . "tests";
        $alo->main_file = $alo->project_path . DIRECTORY_SEPARATOR . "include_once.php";
        $alo->output = $alo->project_path . DIRECTORY_SEPARATOR . "include_once.php.result";
        $alo->run();
        $this->assertStringContainsString("Lorem ipsum dolor sit amet", file_get_contents($alo->output));
    }

    public function testRequire()
    {
        $alo = new Alo;
        $alo->project_path = __DIR__ . DIRECTORY_SEPARATOR . "tests";
        $alo->main_file = $alo->project_path . DIRECTORY_SEPARATOR . "require.php";
        $alo->output = $alo->project_path . DIRECTORY_SEPARATOR . "require.php.result";
        $alo->run();
        $this->assertStringContainsString("Lorem ipsum dolor sit amet", file_get_contents($alo->output));
    }

    public function testRequireOnce()
    {
        $alo = new Alo;
        $alo->project_path = __DIR__ . DIRECTORY_SEPARATOR . "tests";
        $alo->main_file = $alo->project_path . DIRECTORY_SEPARATOR . "require_once.php";
        $alo->output = $alo->project_path . DIRECTORY_SEPARATOR . "require_once.php.result";
        $alo->run();
        $this->assertStringContainsString("Lorem ipsum dolor sit amet", file_get_contents($alo->output));
    }

    public function testCustomInclude()
    {
        $alo = new Alo;
        $alo->project_path = __DIR__ . DIRECTORY_SEPARATOR . "tests";
        $alo->main_file = $alo->project_path . DIRECTORY_SEPARATOR . "custom.php";
        $alo->output = $alo->project_path . DIRECTORY_SEPARATOR . "custom.php.result";
        $alo->run();
        $this->assertStringContainsString("Lorem ipsum dolor sit amet", file_get_contents($alo->output));
    }

    public function testExampleProject()
    {
        $alo = new Alo;
        $alo->project_path = __DIR__ . "/example_project";
        $alo->main_file = "index.php";
        $alo->output = __DIR__ . "/example_project/compiled.php";
        $alo->run();

        $contents = file_get_contents($alo->output);

        $this->assertStringContainsString("Hello,", $contents);
        $this->assertStringContainsString("background-color: #111111", $contents);
        $this->assertStringContainsString("<header>", $contents);
        $this->assertStringContainsString("<footer>", $contents);
        $this->assertStringContainsString("if (\$page ==", $contents);
    }
}
