<?php

namespace App\Support;

class HollandBakeryMenuSqlGenerator
{
    /**
     * @param array<int, array{category:string,url:string,path:string,limit:int,category_id:int}> $sources
     */
    public function generate(array $sources): string
    {
        $lines = [];
        $lines[] = '-- >>> AUTO-GENERATED HOLLAND BAKERY MENU SEED';
        $lines[] = '-- >>> Generated at: ' . now()->format('Y-m-d H:i:s');
        $lines[] = '';
        $lines[] = '-- >>> ADDED: kategori baru sesuai referensi Holland Bakery';
        $lines[] = 'INSERT INTO categories (name) VALUES';

        $categoryLines = [];
        foreach ($sources as $source) {
            $categoryLines[] = "('" . $this->escapeSql($source['category']) . "')";
        }

        $lines[] = implode(",\n", $categoryLines) . ';';
        $lines[] = '';
        $lines[] = '-- >>> ADDED: seed menu baru dari Holland Bakery';
        $lines[] = 'INSERT INTO breads (category_id, name, description, price, stock) VALUES';

        $breadRows = [];
        foreach ($sources as $source) {
            $html = $this->fetchHtml($source['url']);
            $products = $this->extractProducts($html, $source['path'], $source['limit']);

            foreach ($products as $product) {
                $breadRows[] = sprintf(
                    "(%d, '%s', '%s', %d, %d)",
                    $source['category_id'],
                    $this->escapeSql($product['name']),
                    $this->escapeSql($product['description']),
                    (int) $product['price'],
                    (int) $product['stock']
                );
            }
        }

        $lines[] = implode(",\n", $breadRows) . ';';
        $lines[] = '';
        $lines[] = 'SET FOREIGN_KEY_CHECKS=1;';

        return implode("\n", $lines) . "\n";
    }

    /**
     * @return array<int, array{name:string,description:string,price:int,stock:int}>
     */
    private function extractProducts(string $html, string $path, int $limit): array
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);
        $anchors = $xpath->query('//a[contains(@href, "' . $path . '")]');

        $items = [];

        if (! $anchors) {
            return $items;
        }

        foreach ($anchors as $anchor) {
            if (! $anchor instanceof \DOMElement) {
                continue;
            }

            $href = $anchor->getAttribute('href');
            if (! str_contains($href, $path . '/')) {
                continue;
            }

            $title = $this->extractTitle($xpath, $anchor);
            $prices = $this->extractPrices($anchor->textContent);

            if ($title === '' || $prices === []) {
                continue;
            }

            $items[] = [
                'name' => $title,
                'description' => 'Produk populer Holland Bakery.',
                'price' => min($prices),
                'stock' => random_int(8, 40),
            ];
        }

        $items = array_values(array_unique($items, SORT_REGULAR));
        shuffle($items);

        if (count($items) < 7) {
            $items = $this->fallbackProducts($path);
            shuffle($items);
        }

        return array_slice($items, 0, max(1, min($limit, count($items))));
    }

    /**
     * @return array<int, array{name:string,description:string,price:int,stock:int}>
     */
    private function fallbackProducts(string $path): array
    {
        return match ($path) {
            '/menu/breads' => [
                ['name' => 'Chocolate Custard Bread', 'description' => 'Produk populer Holland Bakery.', 'price' => 14700, 'stock' => 25],
                ['name' => 'Danish Coklat Belepotan', 'description' => 'Produk populer Holland Bakery.', 'price' => 19500, 'stock' => 20],
                ['name' => 'Korean Garlic Cream Cheese', 'description' => 'Produk populer Holland Bakery.', 'price' => 17100, 'stock' => 18],
                ['name' => 'Multi Grain Smoked Beef Cheese Sandwich', 'description' => 'Produk populer Holland Bakery.', 'price' => 18300, 'stock' => 16],
                ['name' => 'Roti Abon Sapi', 'description' => 'Produk populer Holland Bakery.', 'price' => 15200, 'stock' => 30],
                ['name' => 'Roti Abon Sapi Pedas', 'description' => 'Produk populer Holland Bakery.', 'price' => 15200, 'stock' => 28],
                ['name' => 'Roti Bakso Ayam', 'description' => 'Produk populer Holland Bakery.', 'price' => 14700, 'stock' => 24],
                ['name' => 'Roti Bakso Sapi', 'description' => 'Produk populer Holland Bakery.', 'price' => 15500, 'stock' => 22],
                ['name' => 'Roti Cheese Raisin', 'description' => 'Produk populer Holland Bakery.', 'price' => 14700, 'stock' => 20],
                ['name' => 'Roti Coklat', 'description' => 'Produk populer Holland Bakery.', 'price' => 11400, 'stock' => 34],
                ['name' => 'Roti Coklat Keju', 'description' => 'Produk populer Holland Bakery.', 'price' => 14300, 'stock' => 30],
                ['name' => 'Roti Coklat Muisjes Gulung', 'description' => 'Produk populer Holland Bakery.', 'price' => 11400, 'stock' => 26],
            ],
            '/menu/chiffon-roll-cakes' => [
                ['name' => 'Bolu Gulung Keju', 'description' => 'Produk populer Holland Bakery.', 'price' => 121500, 'stock' => 12],
                ['name' => 'Bolu Gulung Lemon', 'description' => 'Produk populer Holland Bakery.', 'price' => 109000, 'stock' => 10],
                ['name' => 'Bolu Gulung Pandan', 'description' => 'Produk populer Holland Bakery.', 'price' => 109000, 'stock' => 10],
                ['name' => 'Chiffon Cake Keju', 'description' => 'Produk populer Holland Bakery.', 'price' => 89000, 'stock' => 14],
                ['name' => 'Chiffon Chocolate Chips', 'description' => 'Produk populer Holland Bakery.', 'price' => 91000, 'stock' => 14],
                ['name' => 'Chocolate Muffin', 'description' => 'Produk populer Holland Bakery.', 'price' => 12100, 'stock' => 35],
                ['name' => 'Raisin Muffin', 'description' => 'Produk populer Holland Bakery.', 'price' => 12100, 'stock' => 32],
                ['name' => 'Tiger Roll', 'description' => 'Produk populer Holland Bakery.', 'price' => 121000, 'stock' => 8],
                ['name' => 'Zebra Cake 19 Cm', 'description' => 'Produk populer Holland Bakery.', 'price' => 69000, 'stock' => 9],
                ['name' => 'Bolu Gulung Mocca', 'description' => 'Produk populer Holland Bakery.', 'price' => 109000, 'stock' => 11],
                ['name' => 'Chiffon Cake Pandan', 'description' => 'Produk populer Holland Bakery.', 'price' => 78000, 'stock' => 15],
            ],
            '/menu/cakes' => [
                ['name' => 'Black Forest 15 Cm', 'description' => 'Produk populer Holland Bakery.', 'price' => 193000, 'stock' => 8],
                ['name' => 'Black Forest 19 Cm', 'description' => 'Produk populer Holland Bakery.', 'price' => 298000, 'stock' => 6],
                ['name' => 'Black Forest Cake 15x15 Cm', 'description' => 'Produk populer Holland Bakery.', 'price' => 200000, 'stock' => 7],
                ['name' => 'Brownies Almond', 'description' => 'Produk populer Holland Bakery.', 'price' => 93100, 'stock' => 18],
                ['name' => 'Brownies Keju', 'description' => 'Produk populer Holland Bakery.', 'price' => 95100, 'stock' => 18],
                ['name' => 'Chocolate Crush 15cm', 'description' => 'Produk populer Holland Bakery.', 'price' => 129000, 'stock' => 10],
                ['name' => 'Fun Taartjes', 'description' => 'Produk populer Holland Bakery.', 'price' => 19300, 'stock' => 24],
                ['name' => 'Fun Taartjes Siram Coklat', 'description' => 'Produk populer Holland Bakery.', 'price' => 20400, 'stock' => 20],
                ['name' => 'Japanese Cheesecake', 'description' => 'Produk populer Holland Bakery.', 'price' => 46600, 'stock' => 15],
                ['name' => 'Lemon Taart 15 Cm', 'description' => 'Produk populer Holland Bakery.', 'price' => 152000, 'stock' => 9],
                ['name' => 'Lemon Taart 19 Cm', 'description' => 'Produk populer Holland Bakery.', 'price' => 232000, 'stock' => 8],
                ['name' => 'Lemon Tart 15 X 15 Cm', 'description' => 'Produk populer Holland Bakery.', 'price' => 156000, 'stock' => 8],
            ],
            '/menu/pastry-and-danish' => [
                ['name' => 'Chicken Mushroom Puff', 'description' => 'Produk populer Holland Bakery.', 'price' => 12100, 'stock' => 30],
                ['name' => 'Chicken Pie', 'description' => 'Produk populer Holland Bakery.', 'price' => 12100, 'stock' => 28],
                ['name' => 'Croissant Penyet', 'description' => 'Produk populer Holland Bakery.', 'price' => 11800, 'stock' => 25],
                ['name' => 'Cromboloni Coklat', 'description' => 'Produk populer Holland Bakery.', 'price' => 22100, 'stock' => 18],
                ['name' => 'Cromboloni Lemon', 'description' => 'Produk populer Holland Bakery.', 'price' => 22100, 'stock' => 16],
                ['name' => 'Cromboloni Strawberry', 'description' => 'Produk populer Holland Bakery.', 'price' => 22100, 'stock' => 16],
                ['name' => 'Cromboloni Sweet Cheese', 'description' => 'Produk populer Holland Bakery.', 'price' => 22100, 'stock' => 16],
                ['name' => 'Danish Chocolate', 'description' => 'Produk populer Holland Bakery.', 'price' => 14300, 'stock' => 26],
                ['name' => 'Danish Keju Apik', 'description' => 'Produk populer Holland Bakery.', 'price' => 17200, 'stock' => 20],
                ['name' => 'Danish Raisin', 'description' => 'Produk populer Holland Bakery.', 'price' => 13800, 'stock' => 20],
                ['name' => 'Kue Soes', 'description' => 'Produk populer Holland Bakery.', 'price' => 10300, 'stock' => 40],
                ['name' => 'Pisang Bolen Box Isi 8 Pcs', 'description' => 'Produk populer Holland Bakery.', 'price' => 60000, 'stock' => 12],
            ],
            '/menu/cookies' => [
                ['name' => 'Chokoreto Cookies', 'description' => 'Produk populer Holland Bakery.', 'price' => 77000, 'stock' => 18],
                ['name' => 'Cokelat Hati Toples Segi 4', 'description' => 'Produk populer Holland Bakery.', 'price' => 77000, 'stock' => 16],
                ['name' => 'Cokelat Hati Toples Segi 8', 'description' => 'Produk populer Holland Bakery.', 'price' => 96500, 'stock' => 14],
                ['name' => 'Kaasstengels Toples Segi 4', 'description' => 'Produk populer Holland Bakery.', 'price' => 142000, 'stock' => 10],
                ['name' => 'Kaasstengels Toples Segi 8', 'description' => 'Produk populer Holland Bakery.', 'price' => 154000, 'stock' => 8],
                ['name' => 'Lidah Kucing Toples Segi 4', 'description' => 'Produk populer Holland Bakery.', 'price' => 93500, 'stock' => 12],
                ['name' => 'Nastar Jambu ( Satuan )', 'description' => 'Produk populer Holland Bakery.', 'price' => 8800, 'stock' => 80],
                ['name' => 'Nastar Toples Segi 4', 'description' => 'Produk populer Holland Bakery.', 'price' => 121500, 'stock' => 10],
                ['name' => 'Nastar Toples Segi 8', 'description' => 'Produk populer Holland Bakery.', 'price' => 142000, 'stock' => 8],
                ['name' => 'Putri Salju Toples Segi 4', 'description' => 'Produk populer Holland Bakery.', 'price' => 77000, 'stock' => 12],
                ['name' => 'Putri Salju Toples Segi 8', 'description' => 'Produk populer Holland Bakery.', 'price' => 96500, 'stock' => 10],
                ['name' => 'Roti Bagelen', 'description' => 'Produk populer Holland Bakery.', 'price' => 33700, 'stock' => 24],
            ],
            '/menu/traditional-snacks' => [
                ['name' => 'Arem-arem (lontong)', 'description' => 'Produk populer Holland Bakery.', 'price' => 9600, 'stock' => 40],
                ['name' => 'Bika Ambon', 'description' => 'Produk populer Holland Bakery.', 'price' => 147000, 'stock' => 12],
                ['name' => 'Bika Ambon Cup', 'description' => 'Produk populer Holland Bakery.', 'price' => 10400, 'stock' => 24],
                ['name' => 'Bika Ambon Potong', 'description' => 'Produk populer Holland Bakery.', 'price' => 7700, 'stock' => 30],
                ['name' => 'Bugis Mandi', 'description' => 'Produk populer Holland Bakery.', 'price' => 7200, 'stock' => 28],
                ['name' => 'Coconut Sugar Steamed Cake', 'description' => 'Produk populer Holland Bakery.', 'price' => 7100, 'stock' => 28],
                ['name' => 'Kroket Daging', 'description' => 'Produk populer Holland Bakery.', 'price' => 11400, 'stock' => 32],
                ['name' => 'Kue Ku Ketan / Kue Ku Kacang Hijau', 'description' => 'Produk populer Holland Bakery.', 'price' => 6600, 'stock' => 36],
                ['name' => 'Kue Pepe Roll', 'description' => 'Produk populer Holland Bakery.', 'price' => 7700, 'stock' => 30],
                ['name' => 'Lemper Ayam', 'description' => 'Produk populer Holland Bakery.', 'price' => 10800, 'stock' => 34],
                ['name' => 'Nastar Jambu ( Isi 10 )', 'description' => 'Produk populer Holland Bakery.', 'price' => 82500, 'stock' => 18],
                ['name' => 'Pastel Ayam', 'description' => 'Produk populer Holland Bakery.', 'price' => 10800, 'stock' => 34],
            ],
            default => [],
        };
    }

    private function extractTitle(\DOMXPath $xpath, \DOMElement $anchor): string
    {
        $heading = $xpath->query('.//h1|.//h2|.//h3|.//h4|.//h5|.//h6', $anchor);
        if ($heading && $heading->length > 0) {
            $title = trim(preg_replace('/\s+/u', ' ', (string) $heading->item(0)->textContent));
            return $this->cleanupTitle($title);
        }

        $text = trim(preg_replace('/\s+/u', ' ', $anchor->textContent));
        return $this->cleanupTitle($text);
    }

    private function cleanupTitle(string $title): string
    {
        $title = preg_replace('/\bRp\.?\s*[\d.,]+/iu', '', $title) ?? $title;
        $title = preg_replace('/\badded to cart\b|\badd to cart\b/iu', '', $title) ?? $title;
        $title = preg_replace('/\s+/u', ' ', $title) ?? $title;

        return trim($title);
    }

    /**
     * @return array<int, int>
     */
    private function extractPrices(string $text): array
    {
        preg_match_all('/Rp\.?\s*([\d.,]+)/iu', $text, $matches);

        $prices = [];
        foreach ($matches[1] ?? [] as $match) {
            $normalized = (int) preg_replace('/[^\d]/', '', $match);
            if ($normalized > 0) {
                $prices[] = $normalized;
            }
        }

        return $prices;
    }

    private function fetchHtml(string $url): string
    {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => implode("\r\n", [
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0 Safari/537.36',
                    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                ]),
                'timeout' => 30,
            ],
            'https' => [
                'method' => 'GET',
                'header' => implode("\r\n", [
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0 Safari/537.36',
                    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                ]),
                'timeout' => 30,
            ],
        ]);

        $html = @file_get_contents($url, false, $context);

        if ($html === false || trim($html) === '') {
            throw new \RuntimeException('Gagal mengambil halaman: ' . $url);
        }

        return $html;
    }

    private function escapeSql(string $value): string
    {
        return str_replace(["\\", "'"], ["\\\\", "''"], $value);
    }
}
