<?php
namespace Larakit\Verstak;

use Illuminate\Console\Command;

class CommandVerstakExample extends Command {
    
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'larakit:verstak:example';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Пример проекта на ВЕРСТАКе';
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        \File::copyDirectory(realpath(__DIR__ . '/../../example/'), public_path(VerstakManager::$prefix));
        $this->info('SUCCESS!');
    }
    
    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() {
        return [
        ];
    }
    
    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions() {
        return [
        ];
    }
    
}
