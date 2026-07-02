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
                    'name' => 'Febre Aftosa', 
                    'description' => 'Vacina obrigatória contra a febre aftosa. Deve ser aplicada em todo o rebanho.'
                ],
                [
                    'name' => 'Brucelose (B19/RB51)', 
                    'description' => 'Vacina obrigatória para fêmeas bovinas entre 3 e 8 meses de idade.'
                ],
                [
                    'name' => 'Raiva Herbívora', 
                    'description' => 'Vacina anual profilática contra a raiva, especialmente em áreas de risco.'
                ],
                [
                    'name' => 'Carbúnculo Sintomático (Manqueira)', 
                    'description' => 'Vacina preventiva contra clostridioses, aplicada a partir dos 3 meses.'
                ],
                [
                    'name' => 'Leptospirose', 
                    'description' => 'Vacina reprodutiva recomendada para fêmeas em idade reprodutiva e touros.'
                ],
                [
                    'name' => 'IBR / BVD', 
                    'description' => 'Vacina contra vírus respiratórios e reprodutivos bovinos.'
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
