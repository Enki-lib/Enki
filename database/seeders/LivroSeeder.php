<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LivroModel;
use App\Models\CategoriaModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class LivroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get category IDs
        $categorias = CategoriaModel::all()->pluck('id_categoria', 'nome_categoria');
        
        // List of Brazilian literature books to search
        $books = [
            'Dom Casmurro Machado de Assis',
            'Memórias Póstumas de Brás Cubas Machado de Assis',
            'Grande Sertão: Veredas João Guimarães Rosa',
            'O Cortiço Aluísio Azevedo',
            'Capitães da Areia Jorge Amado',
            'A Hora da Estrela Clarice Lispector',
            'Quarto de Despejo Carolina Maria de Jesus',
            'O Quinze Rachel de Queiroz',
            'Vidas Secas Graciliano Ramos',
            'O Alienista Machado de Assis',
            'Memórias de um Sargento de Milícias Manuel Antônio de Almeida',
            'Auto da Compadecida Ariano Suassuna',
            'Macunaíma Mário de Andrade',
            'O Guarani José de Alencar',
            'Iracema José de Alencar'
        ];

        foreach ($books as $book) {
            // Search for the book in Google Books API
            $response = Http::get('https://www.googleapis.com/books/v1/volumes', [
                'q' => $book,
                'langRestrict' => 'pt',
                'maxResults' => 1
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['items'][0])) {
                    $bookData = $data['items'][0]['volumeInfo'];
                    
                    // Only add books that have a cover image
                    if (isset($bookData['imageLinks']['thumbnail'])) {
                        // Determine category based on book title or description
                        $category = $this->determineCategory($bookData, $categorias);
                        
                        LivroModel::create([
                            'titulo_livro' => $bookData['title'],
                            'edicao_livro' => $bookData['publisher'] ?? 'Edição não especificada',
                            'ano_publicacao' => $this->formatDate($bookData['publishedDate'] ?? ''),
                            'assunto' => $this->truncateDescription($bookData['description'] ?? 'Descrição não disponível'),
                            'ISBN' => $this->getISBN($bookData['industryIdentifiers'] ?? []),
                            'id_categoria' => $category,
                            'status' => 'Disponível'
                        ]);
                    }
                }
            }
            
            // Sleep to avoid hitting API rate limits
            sleep(1);
        }
    }

    /**
     * Format the date from Google Books API to MySQL format
     */
    private function formatDate($date): string
    {
        if (empty($date)) {
            return date('Y-m-d');
        }

        // Try to parse the date
        $parsedDate = date_parse($date);
        
        // Check if the year is valid (not negative and not too far in the future)
        $year = $parsedDate['year'] ?? date('Y');
        if ($year < 0 || $year > date('Y')) {
            $year = date('Y');
        }

        // Ensure month and day are valid
        $month = max(1, min(12, $parsedDate['month'] ?? 1));
        $day = max(1, min(31, $parsedDate['day'] ?? 1));

        return sprintf('%04d-%02d-%02d', $year, $month, $day);
    }

    /**
     * Get ISBN from industry identifiers
     */
    private function getISBN($identifiers): string
    {
        foreach ($identifiers as $identifier) {
            if ($identifier['type'] === 'ISBN_13') {
                return substr($identifier['identifier'], 0, 13);
            }
            if ($identifier['type'] === 'ISBN_10') {
                return substr($identifier['identifier'], 0, 13);
            }
        }
        return '0000000000000'; // Default ISBN when none is available
    }

    /**
     * Truncate description to fit in the database field
     */
    private function truncateDescription($description): string
    {
        // Truncate to 255 characters (standard VARCHAR length)
        return Str::limit($description, 255, '...');
    }

    /**
     * Determine the category based on book data
     */
    private function determineCategory($bookData, $categorias): int
    {
        // Default to Romance if no category is found
        $defaultCategory = $categorias['Romance'] ?? 1;

        // Check if categories are provided by the API
        if (isset($bookData['categories'])) {
            foreach ($bookData['categories'] as $category) {
                $category = strtolower($category);
                
                if (str_contains($category, 'biografia') || str_contains($category, 'biography')) {
                    return $categorias['Biografia'] ?? $defaultCategory;
                }
                if (str_contains($category, 'drama') || str_contains($category, 'teatro')) {
                    return $categorias['Drama'] ?? $defaultCategory;
                }
                if (str_contains($category, 'romance') || str_contains($category, 'fiction')) {
                    return $categorias['Romance'] ?? $defaultCategory;
                }
            }
        }

        return $defaultCategory;
    }
}
