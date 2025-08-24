<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class NewsApiService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('NEWS_API_KEY', '19c8eb56d09e4fbea580b8c3dd5cdbba');
        $this->baseUrl = 'https://newsapi.org/v2';
    }

    /**
     * Fetch Bitcoin news from NewsAPI
     * 
     * @param int $limit Number of articles to fetch (default: 10)
     * @return array
     */
    public function getBitcoinNews($limit = 10)
    {
        try {
            $fromDate = Carbon::now()->subMonth()->format('Y-m-d'); // Get news from last month
            
            $response = Http::get($this->baseUrl . '/everything', [
                'q' => 'bitcoin',
                'from' => $fromDate,
                'sortBy' => 'publishedAt',
                'apiKey' => $this->apiKey,
                'pageSize' => $limit,
                'language' => 'en'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] === 'ok') {
                    return [
                        'success' => true,
                        'articles' => $this->formatArticles($data['articles']),
                        'totalResults' => $data['totalResults']
                    ];
                }
            }

            Log::error('NewsAPI Error: ' . $response->body());
            return [
                'success' => false,
                'articles' => [],
                'error' => 'Failed to fetch news'
            ];

        } catch (\Exception $e) {
            Log::error('NewsAPI Exception: ' . $e->getMessage());
            
            return [
                'success' => false,
                'articles' => [],
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Format articles to match the expected structure
     * 
     * @param array $articles
     * @return array
     */
    private function formatArticles($articles)
    {
        $formatted = [];

        foreach ($articles as $article) {
            // Skip articles with null or empty essential fields
            if (empty($article['title']) || empty($article['url'])) {
                continue;
            }

            $formatted[] = [
                'title' => $article['title'],
                'description' => $article['description'] ?? 'No description available',
                'content' => $article['content'] ?? $article['description'] ?? 'No content available',
                'url' => $article['url'],
                'image' => $article['urlToImage'] ?? null,
                'published_at' => $article['publishedAt'],
                'source' => $article['source']['name'] ?? 'Unknown Source',
                'author' => $article['author'] ?? 'Unknown Author',
                'label' => 'Bitcoin News', // Adding a label similar to podcast structure
                'links' => [
                    ['url' => $article['url'], 'platform' => 'Read More']
                ]
            ];
        }

        return $formatted;
    }

    /**
     * Get fallback news data in case API fails
     * 
     * @return array
     */
    public function getFallbackNews()
    {
        return [
            'success' => true,
            'articles' => [
                [
                    'title' => 'Bitcoin Market Update',
                    'description' => 'Stay updated with the latest Bitcoin market trends and analysis.',
                    'content' => 'Bitcoin continues to show strong market performance with institutional adoption growing.',
                    'url' => '#',
                    'image' => null,
                    'published_at' => Carbon::now()->toISOString(),
                    'source' => 'Adlef News',
                    'author' => 'Adlef Team',
                    'label' => 'Bitcoin News',
                    'links' => [
                        ['url' => '#', 'platform' => 'Read More']
                    ]
                ]
            ],
            'totalResults' => 1
        ];
    }
}
