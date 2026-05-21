<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = [
            ['key' => 'store_name', 'value' => config('app.name')],
            ['key' => 'cuit', 'value' => '12345678-9'],
            ['key' => 'address', 'value' => 'Av. Libertador 1234, Salta'],
            ['key' => 'phone', 'value' => '+54 387 134-5678'],
            ['key' => 'email_contact', 'value' => 'ventas@mitienda.com'],
            ['key' => 'iva_condition', 'value' => 'Responsable Inscripto'],
            ['key' => 'gross_income', 'value' => '901-123456-1'],
            ['key' => 'pickup_hours', 'value' => 'Lunes a Viernes de 10 a 18 hs, Sábados 10 a 13 hs'],
            ['key' => 'pickup_deadline_days', 'value' => 7],
            ['key' => 'branches', 'value' => json_encode([
                ['nro' => '0001', 'name' => 'Sucursal Centro', 'address' => 'Calle Falsa 123'],
                ['nro' => '0002', 'name' => 'Sucursal Norte', 'address' => 'Av. Siempreviva 742'],
            ])],
        ];

        foreach ($defaults as $item) {
            Setting::updateOrCreate(['key' => $item['key']], ['value' => $item['value']]);
        }
    }
}
