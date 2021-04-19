<?php


namespace IsaEken\Alo;


use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use IsaEken\Alo\Exceptions\DirectoryNotExistsException;
use IsaEken\Alo\Exceptions\FileNotExistsException;

/**
 * Class Alo
 * @package IsaEken\Alo
 */
class Alo
{
    /**
     * @var bool $auto_merge_requires
     */
    public bool $auto_merge_requires = true;

    /**
     * @var string|Stringable $project_path
     */
    public string|Stringable $project_path;

    /**
     * @var string|Stringable $main_file
     */
    public string|Stringable $main_file;

    /**
     * @var string|Stringable $output
     */
    public string|Stringable $output;

    /**
     * @var string|Stringable $contents
     */
    public string|Stringable $contents;

    /**
     * @throws DirectoryNotExistsException
     * @throws FileNotExistsException
     */
    public function run()
    {
        if ($this->project_path !== null && !$this->project_path instanceof Stringable) {
            $this->project_path = Str::of($this->project_path);
        }

        if ($this->main_file !== null && !$this->main_file instanceof Stringable) {
            $this->main_file = Str::of($this->main_file);
        }

        if ($this->output !== null && !$this->output instanceof Stringable) {
            $this->output = Str::of($this->output);
        }

        if ($this->contents !== null && !$this->contents instanceof Stringable) {
            $this->contents = Str::of($this->contents);
        }

        if (! is_dir(realpath($this->project_path->__toString()))) {
            throw new DirectoryNotExistsException;
        }
        else {
            $this->project_path = Str::of(realpath($this->project_path->__toString()));
        }

        if (! file_exists($this->main_file)) {
            if (! file_exists($this->project_path->append(DIRECTORY_SEPARATOR, $this->main_file))) {
                throw new FileNotExistsException;
            }
            else {
                $this->main_file = $this->project_path->append(DIRECTORY_SEPARATOR, $this->main_file);
            }
        }

        $this->contents = Str::of(file_get_contents($this->main_file));

        if ($this->auto_merge_requires) {
            $this->contents = (new Merger($this))->merge()->contents;
        }

        file_put_contents($this->output, $this->contents);
    }
}
