<?php

namespace Database\Seeders;

use App\Models\ShippingState;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShippingStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'Pendiente', 'slug' => 'pending'],
            ['name' => 'Asignado', 'slug' => 'assigned'],
            ['name' => 'En camino', 'slug' => 'in_transit'],
            ['name' => 'Entregado', 'slug' => 'delivered'],
            ['name' => 'Fallido', 'slug' => 'failed'],
            ['name' => 'Cancelado', 'slug' => 'cancelled'],
        ];

        foreach ($statuses as $status) {
            ShippingState::create($status);
        }
    }
}
