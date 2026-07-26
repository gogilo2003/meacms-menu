<?php

namespace MeaCms\Menu\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SpinoffPageCommand extends Command
{
    protected $signature = 'make:page
                            {name? : The name of the page to generate (e.g. Home, About, Contact, Products, CustomPage)}
                            {--all : Spinoff all discovered default and package pages at once}
                            {--force : Overwrite existing files if they exist}';

    protected $aliases = ['meacms:make-page', 'meacms:spinoff-page'];

    protected $description = 'Spinoff page boilerplate code including WebController action method, Vue page resource, and web route inside WebController route group.';

    public function handle(): int
    {
        $discoveredPages = $this->discoverPages();

        if ($this->option('all')) {
            foreach ($discoveredPages as $key => $pageInfo) {
                if ($key === 'custom') {
                    continue;
                }
                $this->spinoffPage($key, $pageInfo['name'], $pageInfo);
            }
            $this->info('All discovered pages have been successfully spun off!');
            return Command::SUCCESS;
        }

        $name = $this->argument('name');

        if (! $name) {
            $options = [];
            foreach ($discoveredPages as $key => $pageInfo) {
                $label = $pageInfo['title'];
                if (isset($pageInfo['package'])) {
                    $label .= " (From {$pageInfo['package']})";
                } elseif ($key !== 'custom') {
                    $label .= ' (Core Default)';
                }
                $options[$key] = $label;
            }

            $selectedKey = $this->choice('Which page would you like to spinoff?', array_values($options));
            $keyMap = array_flip($options);
            $selectedKey = $keyMap[$selectedKey] ?? 'custom';

            if ($selectedKey === 'custom') {
                $name = $this->ask('Enter the custom page name (e.g. Services, Team, Portfolio)');
            } else {
                $name = $discoveredPages[$selectedKey]['name'];
            }
        }

        $studlyName = Str::studly($name);
        $matchedKey = Str::lower($studlyName);
        $pageInfo = $discoveredPages[$matchedKey] ?? [
            'name' => $studlyName,
            'title' => Str::title($studlyName),
            'route_path' => '/' . Str::kebab($studlyName),
            'route_name' => Str::kebab($studlyName),
            'method_name' => Str::camel($studlyName),
            'page_stub' => 'GenericPage.vue.stub',
        ];

        $this->spinoffPage($matchedKey, $studlyName, $pageInfo);

        return Command::SUCCESS;
    }

    protected function discoverPages(): array
    {
        $pages = [
            'home' => [
                'name' => 'Home',
                'title' => 'Home',
                'route_path' => '/',
                'route_name' => 'home',
                'method_name' => 'home',
                'page_stub' => 'Home.vue.stub',
            ],
            'about' => [
                'name' => 'About',
                'title' => 'About Us',
                'route_path' => '/about',
                'route_name' => 'about',
                'method_name' => 'about',
                'page_stub' => 'About.vue.stub',
            ],
            'contact' => [
                'name' => 'Contact',
                'title' => 'Contact Us',
                'route_path' => '/contact',
                'route_name' => 'contact',
                'method_name' => 'contact',
                'page_stub' => 'Contact.vue.stub',
            ],
        ];

        // Discover installed MeaCMS packages
        if (class_exists(\MeaCms\Products\ProductsServiceProvider::class)) {
            $pages['products'] = [
                'name' => 'Products',
                'title' => 'Products Catalog',
                'package' => 'meacms/products',
                'route_path' => '/products/{slug?}',
                'route_name' => 'products',
                'method_name' => 'products',
                'page_stub' => 'Products.vue.stub',
            ];
        }

        if (class_exists(\MeaCms\News\NewsServiceProvider::class)) {
            $pages['news'] = [
                'name' => 'News',
                'title' => 'News & Articles',
                'package' => 'meacms/news',
                'route_path' => '/news/{slug?}',
                'route_name' => 'news',
                'method_name' => 'news',
                'page_stub' => 'NewsArticles.vue.stub',
            ];
        }

        if (class_exists(\MeaCms\Downloads\DownloadsServiceProvider::class)) {
            $pages['downloads'] = [
                'name' => 'Downloads',
                'title' => 'Downloads Center',
                'package' => 'meacms/downloads',
                'route_path' => '/downloads/{slug?}',
                'route_name' => 'downloads',
                'method_name' => 'downloads',
                'page_stub' => 'Downloads.vue.stub',
            ];
        }

        if (class_exists(\MeaCms\Quotes\QuotesServiceProvider::class)) {
            $pages['quote'] = [
                'name' => 'QuoteTrack',
                'title' => 'Quote Tracking & Request',
                'package' => 'meacms/quotes',
                'route_path' => '/quote/track/{code}',
                'route_name' => 'quote-track',
                'method_name' => 'quoteTrack',
                'page_stub' => 'QuoteTrack.vue.stub',
            ];
        }

        $pages['custom'] = [
            'name' => 'Custom',
            'title' => 'Custom New Page...',
            'route_path' => '',
            'route_name' => '',
            'method_name' => '',
            'page_stub' => 'GenericPage.vue.stub',
        ];

        return $pages;
    }

    protected function spinoffPage(string $key, string $studlyName, array $pageInfo): void
    {
        $this->components->info("Spinoff page: {$studlyName}");

        $force = $this->option('force');
        $methodName = $pageInfo['method_name'] ?? Str::camel($studlyName);
        $uri = $pageInfo['route_path'] ?: ('/' . Str::kebab($studlyName));
        $routeName = $pageInfo['route_name'] ?: Str::kebab($studlyName);
        $title = $pageInfo['title'] ?? $studlyName;

        // 1. Ensure/Add Action Method in WebController
        $this->addWebControllerAction($methodName, $studlyName, $title);

        // 2. Generate Vue Resource Page
        $vuePagePath = resource_path("js/Pages/{$studlyName}.vue");
        $vueStubPath = __DIR__ . '/../../../stubs/Pages/' . ($pageInfo['page_stub'] ?? 'GenericPage.vue.stub');

        if (! File::exists($vueStubPath)) {
            $vueStubPath = __DIR__ . '/../../../stubs/Pages/GenericPage.vue.stub';
        }

        $vueContent = File::get($vueStubPath);
        $vueContent = str_replace(
            ['{{name}}', '{{title}}'],
            [$studlyName, $title],
            $vueContent
        );

        if (! File::exists(dirname($vuePagePath))) {
            File::makeDirectory(dirname($vuePagePath), 0755, true);
        }

        if (! File::exists($vuePagePath) || $force) {
            File::put($vuePagePath, $vueContent);
            $this->components->twoColumnDetail('Vue Resource Created', "resources/js/Pages/{$studlyName}.vue");
        } else {
            $this->components->warn("Vue Page resources/js/Pages/{$studlyName}.vue already exists (Use --force to overwrite)");
        }

        // 3. Register Route in WebController group in routes/web.php
        $this->addRouteToWebGroup($uri, $methodName, $routeName);
    }

    protected function addWebControllerAction(string $methodName, string $studlyName, string $title): void
    {
        $controllerPath = app_path('Http/Controllers/WebController.php');
        if (! File::exists($controllerPath)) {
            $this->components->warn('app/Http/Controllers/WebController.php does not exist');
            return;
        }

        $content = File::get($controllerPath);

        if (str_contains($content, "function {$methodName}(") || str_contains($content, "function {$methodName} (")) {
            $this->components->warn("Action method WebController::{$methodName}() already exists");
            return;
        }

        $actionStub = "    public function {$methodName}()\n" .
                      "    {\n" .
                      "        return \\Inertia\\Inertia::render('{$studlyName}', [\n" .
                      "            'title' => '{$title}',\n" .
                      "        ]);\n" .
                      "    }\n";

        $lastBracePos = strrpos($content, '}');
        if ($lastBracePos !== false) {
            $updatedContent = substr_replace($content, "\n" . $actionStub . "\n}", $lastBracePos, 1);
            File::put($controllerPath, $updatedContent);
            $this->components->twoColumnDetail('Action Method Added', "WebController::{$methodName}()");
        }
    }

    protected function addRouteToWebGroup(string $uri, string $methodName, string $routeName): void
    {
        $routePath = base_path('routes/web.php');
        if (! File::exists($routePath)) {
            return;
        }

        $content = File::get($routePath);

        if (str_contains($content, "'{$methodName}'") || str_contains($content, "\"{$methodName}\"")) {
            $this->components->warn("Route for '{$methodName}' already present in routes/web.php");
            return;
        }

        $routeLine = "        Route::get('{$uri}', '{$methodName}')->name('{$routeName}');";

        if (preg_match('/Route::controller\(WebController::class\)\s*->group\(function\s*\(\)\s*\{/s', $content, $matches, PREG_OFFSET_CAPTURE)) {
            $insertPos = $matches[0][1] + strlen($matches[0][0]);
            $updatedContent = substr_replace($content, "\n" . $routeLine, $insertPos, 0);
            File::put($routePath, $updatedContent);
            $this->components->twoColumnDetail('Route Added to WebController Group', "routes/web.php ('{$uri}' -> '{$methodName}')");
        } else {
            File::append($routePath, "\n" . $routeLine . "\n");
            $this->components->twoColumnDetail('Route Appended', "routes/web.php ({$uri})");
        }
    }
}
