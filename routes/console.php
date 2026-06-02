<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Support\HollandBakeryMenuSqlGenerator;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('menu:generate-holland-bakery-sql {--output=database/seeders/holland_bakery_menu_seed.sql}', function () {
    $generator = app(HollandBakeryMenuSqlGenerator::class);

    $sources = [
        [
            'category' => 'Roti',
            'url' => 'https://www.hollandbakery.co.id/menu/breads',
            'path' => '/menu/breads',
            'limit' => 10,
            'category_id' => 1,
        ],
        [
            'category' => 'Chiffon & Roll Cakes',
            'url' => 'https://www.hollandbakery.co.id/menu/chiffon-roll-cakes',
            'path' => '/menu/chiffon-roll-cakes',
            'limit' => 10,
            'category_id' => 2,
        ],
        [
            'category' => 'Cakes',
            'url' => 'https://www.hollandbakery.co.id/menu/cakes',
            'path' => '/menu/cakes',
            'limit' => 10,
            'category_id' => 3,
        ],
        [
            'category' => 'Pastry & Danish',
            'url' => 'https://www.hollandbakery.co.id/menu/pastry-and-danish',
            'path' => '/menu/pastry-and-danish',
            'limit' => 10,
            'category_id' => 4,
        ],
        [
            'category' => 'Cookies',
            'url' => 'https://www.hollandbakery.co.id/menu/cookies',
            'path' => '/menu/cookies',
            'limit' => 10,
            'category_id' => 5,
        ],
        [
            'category' => 'Traditional Snack',
            'url' => 'https://www.hollandbakery.co.id/menu/traditional-snacks',
            'path' => '/menu/traditional-snacks',
            'limit' => 10,
            'category_id' => 6,
        ],
    ];

    $sql = $generator->generate($sources);
    $outputPath = base_path($this->option('output'));

    file_put_contents($outputPath, $sql);

    $this->info('SQL seed generated at: ' . $outputPath);
})->purpose('Generate Holland Bakery menu seed SQL');

Artisan::command('breads:sync-images {--rename=false}', function () {
    $this->comment('Scanning public/images for bread images...');

    $dir = public_path('images');
    if (!is_dir($dir)) {
        $this->error('public/images directory not found.');
        return 1;
    }

    $files = array_values(array_filter(scandir($dir), function ($f) use ($dir) {
        return is_file($dir . DIRECTORY_SEPARATOR . $f) && preg_match('/\.(jpe?g|png|svg)$/i', $f);
    }));

    $this->info('Found ' . count($files) . ' files.');

    $updated = 0;
    $renamed = 0;

    foreach ($files as $file) {
        $slug = pathinfo($file, PATHINFO_FILENAME);
        // normalize: compare with Str::slug of bread name
        $bread = \App\Models\Bread::all()->first(function ($b) use ($slug) {
            return \Illuminate\Support\Str::slug($b->name) === $slug;
        });

        if ($bread) {
            $bread->image_path = 'images/' . $file;
            $bread->save();
            $updated++;

            if ($this->option('rename') && $this->option('rename') != 'false') {
                $newName = str_replace('-', ' ', $slug);
                $newName = ucwords($newName);
                if ($bread->name !== $newName) {
                    $bread->name = $newName;
                    $bread->save();
                    $renamed++;
                }
            }
        }
    }

    $this->info("Updated images for: $updated breads.");
    if ($this->option('rename') && $this->option('rename') != 'false') {
        $this->info("Renamed: $renamed breads.");
    }
})->purpose('Sync breads image_path from public/images and optionally rename breads');
