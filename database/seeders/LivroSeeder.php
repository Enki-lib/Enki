<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LivroModel;
use App\Models\CategoriaModel;

class LivroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get category IDs
        $categorias = CategoriaModel::all()->pluck('id_categoria', 'nome_categoria');
        
        $livros = [
            [
                'titulo_livro' => 'Dom Casmurro',
                'edicao_livro' => 'Edição Especial',
                'ano_publicacao' => '1899-01-01',
                'assunto' => 'Obra-prima de Machado de Assis que narra a história de Bentinho e Capitu, um romance que explora temas como ciúme, traição e as complexidades das relações humanas.',
                'ISBN' => '9788535910681',
                'id_categoria' => $categorias['Romance']
            ],
            [
                'titulo_livro' => 'Memórias Póstumas de Brás Cubas',
                'edicao_livro' => 'Edição Comemorativa',
                'ano_publicacao' => '1881-01-01',
                'assunto' => 'Romance de Machado de Assis narrado por um defunto autor, que conta sua história e reflexões sobre a vida após sua morte.',
                'ISBN' => '9788535910933',
                'id_categoria' => $categorias['Romance']
            ],
            [
                'titulo_livro' => 'Grande Sertão: Veredas',
                'edicao_livro' => 'Edição Biblioteca do Estudante',
                'ano_publicacao' => '1956-01-01',
                'assunto' => 'Obra-prima de Guimarães Rosa que narra a história de Riobaldo, ex-jagunço que conta sua vida no sertão, seus conflitos e seu amor por Diadorim.',
                'ISBN' => '9788535920598',
                'id_categoria' => $categorias['Romance']
            ],
            [
                'titulo_livro' => 'O Cortiço',
                'edicao_livro' => 'Edição Comentada',
                'ano_publicacao' => '1890-01-01',
                'assunto' => 'Romance naturalista de Aluísio Azevedo que retrata a vida em uma habitação coletiva do Rio de Janeiro do século XIX.',
                'ISBN' => '9788535921182',
                'id_categoria' => $categorias['Romance']
            ],
            [
                'titulo_livro' => 'Capitães da Areia',
                'edicao_livro' => 'Edição Especial',
                'ano_publicacao' => '1937-01-01',
                'assunto' => 'Romance de Jorge Amado sobre um grupo de menores abandonados que vivem em um trapiche no areal do cais de Salvador.',
                'ISBN' => '9788535911091',
                'id_categoria' => $categorias['Romance']
            ],
            [
                'titulo_livro' => 'A Hora da Estrela',
                'edicao_livro' => 'Edição Comemorativa',
                'ano_publicacao' => '1977-01-01',
                'assunto' => 'Último romance de Clarice Lispector, conta a história de Macabéa, uma nordestina que vive no Rio de Janeiro.',
                'ISBN' => '9788532508126',
                'id_categoria' => $categorias['Romance']
            ],
            [
                'titulo_livro' => 'Quarto de Despejo',
                'edicao_livro' => 'Edição Revista',
                'ano_publicacao' => '1960-01-01',
                'assunto' => 'Diário de Carolina Maria de Jesus, que relata sua vida na favela do Canindé, em São Paulo.',
                'ISBN' => '9788508171279',
                'id_categoria' => $categorias['Biografia']
            ],
            [
                'titulo_livro' => 'O Quinze',
                'edicao_livro' => 'Edição Especial',
                'ano_publicacao' => '1930-01-01',
                'assunto' => 'Romance de Rachel de Queiroz sobre a seca de 1915 no Ceará e seus impactos na vida dos personagens.',
                'ISBN' => '9788503012125',
                'id_categoria' => $categorias['Romance']
            ],
            [
                'titulo_livro' => 'Vidas Secas',
                'edicao_livro' => 'Edição Comemorativa',
                'ano_publicacao' => '1938-01-01',
                'assunto' => 'Romance de Graciliano Ramos que narra a saga de uma família de retirantes pelo sertão nordestino.',
                'ISBN' => '9788501005588',
                'id_categoria' => $categorias['Romance']
            ],
            [
                'titulo_livro' => 'O Alienista',
                'edicao_livro' => 'Edição Comentada',
                'ano_publicacao' => '1882-01-01',
                'assunto' => 'Novela de Machado de Assis que satiriza a psiquiatria através da história do Dr. Simão Bacamarte.',
                'ISBN' => '9788535910698',
                'id_categoria' => $categorias['Romance']
            ],
            [
                'titulo_livro' => 'Memórias de um Sargento de Milícias',
                'edicao_livro' => 'Edição Anotada',
                'ano_publicacao' => '1854-01-01',
                'assunto' => 'Romance de Manuel Antônio de Almeida que retrata o Rio de Janeiro do início do século XIX através das aventuras de Leonardo.',
                'ISBN' => '9788535910728',
                'id_categoria' => $categorias['Romance']
            ],
            [
                'titulo_livro' => 'Auto da Compadecida',
                'edicao_livro' => 'Edição Especial',
                'ano_publicacao' => '1955-01-01',
                'assunto' => 'Peça teatral de Ariano Suassuna que mistura cultura popular, religiosidade e comédia no sertão nordestino.',
                'ISBN' => '9788520937822',
                'id_categoria' => $categorias['Drama']
            ],
            [
                'titulo_livro' => 'Macunaíma',
                'edicao_livro' => 'Edição Crítica',
                'ano_publicacao' => '1928-01-01',
                'assunto' => 'Romance modernista de Mário de Andrade sobre o anti-herói brasileiro Macunaíma, o herói sem nenhum caráter.',
                'ISBN' => '9788520923979',
                'id_categoria' => $categorias['Romance']
            ],
            [
                'titulo_livro' => 'O Guarani',
                'edicao_livro' => 'Edição Histórica',
                'ano_publicacao' => '1857-01-01',
                'assunto' => 'Romance histórico de José de Alencar que narra o amor entre o índio Peri e a portuguesa Ceci.',
                'ISBN' => '9788526022300',
                'id_categoria' => $categorias['Romance']
            ],
            [
                'titulo_livro' => 'Iracema',
                'edicao_livro' => 'Edição Comemorativa',
                'ano_publicacao' => '1865-01-01',
                'assunto' => 'Romance indianista de José de Alencar que conta a história de amor entre a índia Iracema e o português Martim.',
                'ISBN' => '9788535910865',
                'id_categoria' => $categorias['Romance']
            ]
        ];

        foreach ($livros as $livro) {
            LivroModel::create($livro);
        }
    }
}
