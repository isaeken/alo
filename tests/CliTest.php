<?php

namespace IsaEken\Alo\Test;

use PHPUnit\Framework\TestCase;

class CliTest extends TestCase
{
    private function executeLine(): string
    {
        return PHP_BINARY . " " . realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "bin" . DIRECTORY_SEPARATOR . "alo.php") . " ";
    }

    public function testHelp()
    {
        $this->assertStringContainsString("Usage:", shell_exec($this->executeLine() . " --help"));
    }

    public function testCompile()
    {
        $project_directory = realpath(__DIR__ . DIRECTORY_SEPARATOR . "example_project");
        $output_file = $project_directory . DIRECTORY_SEPARATOR . "compiled.php";

        shell_exec($this->executeLine() . " " . $project_directory . " " . "index.php" . " " . $output_file . " --watch=false");

        $contents = file_get_contents($output_file);

        $this->assertStringContainsString("Hello,", $contents);
        $this->assertStringContainsString("background-color: #111111", $contents);
        $this->assertStringContainsString("<header>", $contents);
        $this->assertStringContainsString("<footer>", $contents);
        $this->assertStringContainsString("if (\$page ==", $contents);
    }
}
