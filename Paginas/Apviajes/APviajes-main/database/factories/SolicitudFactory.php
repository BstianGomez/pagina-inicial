<?php

namespace Database\Factories;

use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SolicitudFactory extends Factory
{
    protected $model = Solicitud::class;

    public function definition()
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $estados = ['aprobado', 'pendiente', 'rechazado'];
        $destinos = ['Santiago', 'Valdivia', 'Concepción', 'Antofagasta', 'Puerto Montt', 'Temuco', 'Lima'];
        $cecos = ['20001', '20004', '20131', '20139', '20136', '20147', '20148'];
        return [
            'user_id' => $user->id,
            'tipo' => $this->faker->randomElement(['interno', 'externo']),
            'nombre_externo' => $this->faker->name(),
            'correo_externo' => $this->faker->safeEmail(),
            'rut' => $this->faker->numerify('########-#'),
            'fecha_nacimiento' => $this->faker->date(),
            'cargo_externo' => $this->faker->jobTitle(),
            'ceco' => $this->faker->randomElement($cecos),
            'destino' => $this->faker->randomElement($destinos),
            'fecha_viaje' => $this->faker->dateTimeBetween('-6 months', '+2 months'),
            'fecha_retorno' => $this->faker->dateTimeBetween('+1 days', '+3 months'),
            'motivo' => $this->faker->sentence(3),
            'alojamiento' => $this->faker->boolean(),
            'traslado' => $this->faker->boolean(),
            'gastos' => [],
            'estado' => $this->faker->randomElement($estados),
            'aprobado_por' => null,
            'aprobado_en' => null,
            'comentario_aprobador' => null,
            'rechazado_por' => null,
            'rechazado_en' => null,
            'comentario_rechazo' => null,
            'gestionado_por' => null,
            'gestionado_en' => null,
        ];
    }
}
