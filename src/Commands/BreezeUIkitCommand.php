<?php

namespace Zacksmash\BreezeUIkit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class BreezeUIkitCommand extends Command
{
    public $signature = 'breeze-ui:uikit';

    public $description = 'Install UIkit preset, with views and resources';

    public function handle()
    {
        $this->publishAssets();
        $this->updateWebpackUrl();
        // $this->updateRoutes();

        $this->comment('UIkit is now installed.');
        $this->info('Remember to run npm i && npm run dev!');
    }

    protected function publishAssets()
    {
        File::delete(base_path('tailwind.config.js'));

        File::deleteDirectory(resource_path('css'));

        $this->callSilent('vendor:publish', ['--tag' => 'breeze-uikit-resources', '--force' => true]);
    }

    protected function updateWebpackUrl()
    {
        File::put(
            base_path('webpack.mix.js'),
            str_replace(
                'http://CHANGE_ME.test',
                env('APP_URL'),
                File::get(base_path('webpack.mix.js'))
            )
        );
    }

    protected function updateRoutes()
    {
        File::append(
            base_path('routes/web.php'),
            "\nRoute::prefix('user')->middleware(['auth', 'verified'])->group(function () {\n\tRoute::view('profile', 'profile.show');\n});\n"
        );
    }
}
