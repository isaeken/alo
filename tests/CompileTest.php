<?php

namespace IsaEken\Alo\Test;

use Illuminate\Support\Str;
use IsaEken\Alo\Alo;
use PHPUnit\Framework\TestCase;

class CompileTest extends TestCase
{
    public function testBasic()
    {
        $alo = new Alo;
        $alo->auto_merge_requires = true;
        $alo->project_path = Str::of(__DIR__ . "/../test");
        $alo->main_file = Str::of("index.php");
        $alo->output = Str::of("output.php");
        $alo->run();
        $contents = file_get_contents(__DIR__ . "/../output.php");

        $this->assertStringContainsString("HEADER", $contents);
        $this->assertStringContainsString("HOME", $contents);
        $this->assertStringContainsString("FOOTER", $contents);
    }
}
