<?php

namespace Database\Populate;

use App\Models\Tag;

class TagPopulate
{
    public static function populate(): void
    {
        try {
            $tagsDataList = [
                [
                    'name' => 'Reprodutor', 
                    'description' => 'Animal utilizado como reprodutor na propriedade.'
                ],
                [
                    'name' => 'Matriz', 
                    'description' => 'Vaca matriz de novos animais'
                ],
                [
                    'name' => 'Confinamento', 
                    'description' => 'Animal separado confinamento de engorda.'
                ],
                [
                    'name' => 'Venda', 
                    'description' => 'Animal separado para futura venda.'
                ],
                [
                    'name' => 'Observação', 
                    'description' => 'Animal com problemas, recomenda-se observacao constante.'
                ],
                [
                    'name' => 'Isolamento', 
                    'description' => 'Animal isolado para a nao propagacao de doencas.'
                ]
            ];

            foreach ($tagsDataList as $TagData) {
                $existing = Tag::where(['name' => $TagData['name']]);
                
                if (empty($existing)) {
                    $tag = new Tag($TagData);
                    $tag->save();
                }
            }

            echo "Tags populated successfully.\n";
        } catch (\Throwable $e) {
            echo "Erro ao populate Tags: " . $e->getMessage() . PHP_EOL;
        }
    }
}
