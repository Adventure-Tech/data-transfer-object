<?php

namespace AdventureTech\DataTransferObject\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Pluralizer;

class DtoMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:dto {name}';

    /**
     * Name
     *
     * @var string
     */
    protected $name;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a new DTO class';

    /**
     * Filesystem instance
     * @var Filesystem
     */
    protected Filesystem $files;

    /**
     * Create a new command instance.
     * @param  Filesystem  $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->name = $this->argument('name');

        $path = $this->getSourceFilePath();

        $this->makeDirectory(dirname($path));

        $contents = $this->getSourceFile();

        if (!$this->files->exists($path)) {
            $this->files->put($path, $contents);
            $this->info("DTO : {$path} was created");
        } else {
            $this->error("DTO : {$path} already exits");
        }

    }

    /**
     * Return the stub file path
     * @return string
     *
     */
    public function getStubPath(): string
    {
        return __DIR__.'/../../../resources/stubs/dto.stub';
    }

    /**
     **
     * Map the stub variables present in stub to its value
     *
     * @return array
     *
     */
    public function getStubVariables(): array
    {
        return [
            'NAMESPACE' => 'App\\Dto',
            'CLASS_NAME' => $this->name,
        ];
    }

    /**
     * Get the stub path and the stub variables
     *
     * @return string
     *
     */
    public function getSourceFile(): string
    {
        return $this->getStubContents($this->getStubPath(), $this->getStubVariables());
    }


    /**
     * Replace the stub variables(key) with the desire value
     *
     * @param $stub
     * @param  array  $stubVariables
     * @return string|array|bool
     */
    public function getStubContents($stub, array $stubVariables = []): string|array|bool
    {
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace) {
            $contents = str_replace($search, $replace, $contents);
        }

        return $contents;

    }

    /**
     * Get the full path of generate class
     *
     * @return string
     */
    public function getSourceFilePath(): string
    {
        return base_path('app/Dto').'/'.$this->name.'.php';
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory(string $path): string
    {
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }
}
