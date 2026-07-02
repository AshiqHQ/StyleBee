<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class KnowledgeBaseService
{
    /**
     * Search your MySQL data for anything relevant to the customer's question,
     * and return it as one text block Gemini can read.
     *
     * Built for the store's actual schema:
     *   products(id, name, slug, short_description, description, regular_price,
     *            sale_price, SKU, stock_status, featured, quantity,
     *            category_id -> categories.id, brand_id -> brands.id)
     *   categories(id, name, slug)
     *   brands(id, name, slug)
     *   orders(id, user_id, subtotal, discount, tax, total, status, delivered_date,
     *          canceled_date, created_at) + order_items(order_id, product_id, price, quantity)
     *   reviews(id, product_id, name, rating, comment)
     */
    public function findRelevantContext(string $question): string
    {
        $keywords = $this->extractKeywords($question);

        $contextPieces = [];

        // ---------------------------------------------------------------
        // 1) PRODUCTS — search name, descriptions, and joined category/brand
        //    names, since customers often ask "do you have Aarong panjabi"
        //    or "any red items in girls clothing".
        // ---------------------------------------------------------------
        $products = DB::table('products')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->where(function ($q) use ($keywords) {
                foreach ($keywords as $word) {
                    $q->orWhere('products.name', 'LIKE', "%{$word}%")
                      ->orWhere('products.short_description', 'LIKE', "%{$word}%")
                      ->orWhere('products.description', 'LIKE', "%{$word}%")
                      ->orWhere('categories.name', 'LIKE', "%{$word}%")
                      ->orWhere('brands.name', 'LIKE', "%{$word}%");
                }
            })
            ->limit(6)
            ->get([
                'products.name',
                'products.short_description',
                'products.regular_price',
                'products.sale_price',
                'products.stock_status',
                'products.quantity',
                'categories.name as category_name',
                'brands.name as brand_name',
            ]);

        foreach ($products as $p) {
            $price = $p->sale_price ?? $p->regular_price;
            $wasPrice = $p->sale_price ? " (was {$p->regular_price} BDT)" : '';
            $stock = $p->stock_status === 'instock' ? "In stock ({$p->quantity} left)" : 'Out of stock';

            $contextPieces[] = "Product: {$p->name} | Category: {$p->category_name} | Brand: {$p->brand_name} "
                . "| Price: {$price} BDT{$wasPrice} | {$stock} | {$p->short_description}";
        }

        // ---------------------------------------------------------------
        // 2) CATEGORIES & BRANDS — helps answer "what categories do you
        //    have?" or "which brands do you sell?" even with no keyword hit
        //    on a specific product.
        // ---------------------------------------------------------------
        if (str_contains(strtolower($question), 'categor')) {
            $categories = DB::table('categories')->pluck('name');
            if ($categories->isNotEmpty()) {
                $contextPieces[] = "Available categories: " . $categories->implode(', ');
            }
        }

        if (str_contains(strtolower($question), 'brand')) {
            $brands = DB::table('brands')->pluck('name');
            if ($brands->isNotEmpty()) {
                $contextPieces[] = "Available brands: " . $brands->implode(', ');
            }
        }

        // ---------------------------------------------------------------
        // 3) REVIEWS — lets the bot answer "what do people say about X"
        // ---------------------------------------------------------------
        $reviews = DB::table('reviews')
            ->join('products', 'reviews.product_id', '=', 'products.id')
            ->where(function ($q) use ($keywords) {
                foreach ($keywords as $word) {
                    $q->orWhere('products.name', 'LIKE', "%{$word}%");
                }
            })
            ->limit(3)
            ->get(['products.name as product_name', 'reviews.rating', 'reviews.comment']);

        foreach ($reviews as $r) {
            $contextPieces[] = "Review for {$r->product_name}: {$r->rating}/5 stars - \"{$r->comment}\"";
        }

        // ---------------------------------------------------------------
        // 4) ORDERS — ONLY for the logged-in customer's own orders.
        //    This must stay behind auth()->check() so one customer can
        //    never see another customer's order info.
        // ---------------------------------------------------------------
        if (auth()->check()) {
            $orders = DB::table('orders')
                ->where('user_id', auth()->id())
                ->orderByDesc('created_at')
                ->limit(5)
                ->get(['id', 'status', 'total', 'delivered_date', 'canceled_date', 'created_at']);

            foreach ($orders as $o) {
                $items = DB::table('order_items')
                    ->join('products', 'order_items.product_id', '=', 'products.id')
                    ->where('order_id', $o->id)
                    ->get(['products.name', 'order_items.quantity']);

                $itemList = $items->map(fn ($i) => "{$i->quantity}x {$i->name}")->implode(', ');

                $extra = match ($o->status) {
                    'delivered' => "Delivered on {$o->delivered_date}",
                    'canceled'  => "Canceled on {$o->canceled_date}",
                    default     => 'Currently being processed',
                };

                $contextPieces[] = "Order #{$o->id} | Status: {$o->status} ({$extra}) "
                    . "| Total: {$o->total} BDT | Items: {$itemList} | Placed: {$o->created_at}";
            }
        }

        if (empty($contextPieces)) {
            return "No specific matching information was found in the store database for this question.";
        }

        return implode("\n\n", $contextPieces);
    }

    /**
     * Very simple keyword extractor: splits the question into words,
     * removes common "stop words" that aren't useful for searching.
     */
    protected function extractKeywords(string $question): array
    {
        $stopWords = ['the', 'is', 'are', 'a', 'an', 'do', 'does', 'i', 'my', 'to', 'for',
                       'of', 'and', 'how', 'what', 'when', 'where', 'can', 'you', 'me', 'in', 'on',
                       'have', 'has', 'any', 'want', 'need', 'looking'];

        $words = preg_split('/\s+/', strtolower(preg_replace('/[^\w\s]/', '', $question)));

        $keywords = array_filter($words, function ($word) use ($stopWords) {
            return strlen($word) > 2 && !in_array($word, $stopWords);
        });

        return empty($keywords) ? [$question] : array_values($keywords);
    }
}
