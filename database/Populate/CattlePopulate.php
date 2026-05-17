<?php

namespace Database\Populate;
use App\Models\Cattle;

class CattlePopulate
{
    public static function populate(): void
    {
        try {
            for ($i = 0; $i < 10; $i++) {

                $cattleDataList = [
                    [
                        'breed' => 'Angus' . '-'. $i,
                        'purchase_value_in_cents' => 150000,
                        'purchase_date' => '2023-01-15',
                        'purchase_type' => 'Leilão',
                        'state' => 'active',
                        'registered_by_user_id' => 1
                    ],
                    [
                        'breed' => 'Hereford' . '-'. $i,
                        'purchase_value_in_cents' => 120000,
                        'purchase_date' => '2023-02-20',
                        'purchase_type' => 'Compra direta',
                        'state' => 'active',
                        'registered_by_user_id' => 1
                    ],
                    [
                        'breed' => 'Nelore' . '-'. $i,
                        'purchase_value_in_cents' => 100000,
                        'purchase_date' => '2023-03-10',
                        'purchase_type' => '',
                        'state' => 'active',
                        'registered_by_user_id' => 1
                    ],
                ];

                foreach ($cattleDataList as $cattleData) {
                    $cattle = new Cattle($cattleData);
                    $cattle->save();
                }
            }

            echo "Cattle populated successfully.\n";
        } catch (\Throwable $e) {
            echo "Erro ao populate cattle: " . $e->getMessage() . PHP_EOL;
        }
    }
}
