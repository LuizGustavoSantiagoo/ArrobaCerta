<?php

namespace Database\Populate;

use App\Models\Tag;

class TagPopulate
{
    public static function populate(): void
    {
        try {
            $tag = [
                [
                    'name' => 'Reprodutor', 
                ],
                [
                    'name' => 'Matriz', 
                ],
                [
                    'name' => 'Confinamento', 
                ],
                [
                    'name' => 'Venda', 
                ],
                [
                    'name' => 'Observação', 
                ],
                [
                    'name' => 'Isolamento', 
                ]
            ];

            foreach ($tag as $tagData) {
                $existing = Tag::where(['name' => $tagData['name']]);
                
                if (empty($existing)) {
                    $tag = new Tag($tagData);
                    $tag->save();
                }
            }

            echo "tags populated successfully.\n";
        } catch (\Throwable $e) {
            echo "Erro ao populate tags: " . $e->getMessage() . PHP_EOL;
        }
    }
}
