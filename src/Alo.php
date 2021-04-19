<?php


namespace IsaEken\Alo;


use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use IsaEken\Alo\Exceptions\DirectoryNotExistsException;
use IsaEken\Alo\Exceptions\FileNotExistsException;

class Alo
{
    /**
     * @var bool $auto_merge_requires
     */
    public bool $auto_merge_requires = true;

    /**
     * @var Stringable $project_path
     */
    public Stringable $project_path;

    /**
     * @var Stringable $main_file
     */
    public Stringable $main_file;

    /**
     * @var Stringable $output
     */
    public Stringable $output;

    /**
     * @var Stringable $contents
     */
    public Stringable $contents;

    /**
     * @throws DirectoryNotExistsException
     * @throws FileNotExistsException
     */
    public function run()
    {
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
