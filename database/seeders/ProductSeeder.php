<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Redness products
            [
                'name' => 'Gentle Calming Serum',
                'description' => 'A soothing serum with niacinamide and centella asiatica to reduce redness and inflammation. Perfect for sensitive skin types.',
                'image' => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=400&h=300&fit=crop',
                'price' => 24.99,
                'original_price' => 34.99,
                'rating' => 4.5,
                'reviews' => 1250,
                'category' => 'Serum',
                'benefits' => ['Reduces redness', 'Anti-inflammatory', 'Gentle formula', 'Sensitive skin safe'],
                'concern_type' => 'redness',
                'is_active' => true,
            ],

            // Texture products
            [
                'name' => 'Exfoliating AHA/BHA Toner',
                'description' => 'Dual-action toner with glycolic acid and salicylic acid to smooth skin texture and minimize pores.',
                'image' => 'https://plus.unsplash.com/premium_photo-1680740103875-048f39fe05d7?q=80&w=2340&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'price' => 22.50,
                'original_price' => 29.99,
                'rating' => 4.6,
                'reviews' => 2100,
                'category' => 'Toner',
                'benefits' => ['Smooths texture', 'Minimizes pores', 'Gentle exfoliation', 'Brightening'],
                'concern_type' => 'texture',
                'is_active' => true,
            ],

            // Oiliness products
            [
                'name' => 'Oil Control Cleanser',
                'description' => 'Deep cleansing gel with salicylic acid and tea tree oil to control excess oil and prevent breakouts.',
                'image' => 'https://images.unsplash.com/photo-1556228578-8c89e6adf883?w=400&h=300&fit=crop',
                'price' => 16.99,
                'original_price' => 21.99,
                'rating' => 4.2,
                'reviews' => 1780,
                'category' => 'Cleanser',
                'benefits' => ['Controls oil', 'Deep cleansing', 'Prevents breakouts', 'Refreshing'],
                'concern_type' => 'oiliness',
                'is_active' => true,
            ],

            // Dryness products
            [
                'name' => 'Hydrating Hyaluronic Serum',
                'description' => 'Intensive hydrating serum with multiple types of hyaluronic acid to plump and moisturize dry skin.',
                'image' => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=400&h=300&fit=crop',
                'price' => 28.99,
                'original_price' => 39.99,
                'rating' => 4.7,
                'reviews' => 3200,
                'category' => 'Serum',
                'benefits' => ['Deep hydration', 'Plumps skin', 'Long-lasting', 'All skin types'],
                'concern_type' => 'dryness',
                'is_active' => true,
            ],

            // Pore Visibility products
            [
                'name' => 'Pore Minimizing Serum',
                'description' => 'Concentrated serum with niacinamide and zinc to visibly reduce pore size and control oil production.',
                'image' => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=400&h=300&fit=crop',
                'price' => 21.99,
                'original_price' => 27.99,
                'rating' => 4.3,
                'reviews' => 1650,
                'category' => 'Serum',
                'benefits' => ['Minimizes pores', 'Controls oil', 'Smooths skin', 'Fast-absorbing'],
                'concern_type' => 'poreVisibility',
                'is_active' => true,
            ],

            // Dark Circles products
            [
                'name' => 'Brightening Eye Cream',
                'description' => 'Caffeine-infused eye cream with vitamin C to reduce dark circles and puffiness around the delicate eye area.',
                'image' => 'https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?w=400&h=300&fit=crop',
                'price' => 26.99,
                'original_price' => 34.99,
                'rating' => 4.4,
                'reviews' => 2050,
                'category' => 'Eye Care',
                'benefits' => ['Reduces dark circles', 'Anti-puffiness', 'Brightening', 'Gentle formula'],
                'concern_type' => 'darkCircles',
                'is_active' => true,
            ],

            // Acne Scarring products
            [
                'name' => 'Vitamin C Brightening Serum',
                'description' => 'Potent vitamin C serum with 20% L-ascorbic acid to fade acne scars and brighten overall complexion.',
                'image' => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=400&h=300&fit=crop',
                'price' => 29.99,
                'original_price' => 42.99,
                'rating' => 4.6,
                'reviews' => 2800,
                'category' => 'Serum',
                'benefits' => ['Fades scars', 'Brightening', 'Antioxidant', 'Even skin tone'],
                'concern_type' => 'acneScarring',
                'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}