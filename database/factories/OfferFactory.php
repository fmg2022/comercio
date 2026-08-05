<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Offer>
 */
class OfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $offersNames = [
            "Oferta Flash de Invierno",
            "Descuento Especial Fin de Semana",
            "Promoción de Lanzamiento Exprés",
            "Oferta de Aniversario Premium",
            "Super Descuento de Primavera",
            "Liquidación de Temporada Otoño",
            "Oferta Exprés de 24 Horas",
            "Descuento por Volumen Mayorista",
            "Promoción de Fidelidad Cliente",
            "Oferta de Reapertura Sucursal",
            "Descuento por Nuevo Usuario",
            "Oferta Especial de Vacaciones",
            "Promoción de San Valentín Especial",
            "Oferta de Black Weekend Larga",
            "Descuento de Media Temporada",
            "Oferta de Lanzamiento Exclusivo",
            "Promoción de Cyber Monday",
            "Oferta de Última Oportunidad",
            "Descuento por Cumpleaños Anual",
            "Oferta de Clientes VIP Selección",
            "Promoción de Fin de Año",
            "Oferta de Regreso a Clases",
            "Descuento en Segunda Unidad",
            "Oferta de Mes de Aniversario",
            "Promoción de Día del Padre",
            "Oferta de Día de la Madre",
            "Descuento por Recomendación",
            "Oferta de Noche de Reyes",
            "Promoción de Sorteo Especial",
            "Oferta de Clausura de Colección"
        ];
        $startDate = $this->faker->dateTimeBetween('-1 months', '+1 week');
        $endDate = Carbon::parse($startDate)->addDays(rand(5, 21));
        $type_state = 'active';

        $startDate > now()
            ? $type_state = 'pending'
            : ($endDate > now() ?: $type_state = 'expired');

        return [
            'name' => $offersNames[array_rand($offersNames)],
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate,
            'offer_state_id' => DB::table('offer_states')->where('slug', $type_state)->first()->id,
            'offer_template_id' => DB::table('offer_templates')->inRandomOrder()->first()->id,
        ];
    }
}
