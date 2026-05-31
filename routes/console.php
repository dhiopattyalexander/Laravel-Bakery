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
