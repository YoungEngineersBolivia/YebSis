<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

class Cliente extends Model
{
    /**
     * Trait para usar factories de modelos, útil para generar datos de prueba.
     */
    use HasFactory;

    /**
     * Trait para enviar notificaciones a los clientes.
     * Solo inclúyelo si planeas enviar notificaciones directamente a los clientes.
     */
    use Notifiable;

    /**
     * Los atributos que son asignables masivamente.
     * Define aquí los campos que se pueden llenar de forma segura desde un formulario.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'direccion',
        // Agrega aquí cualquier otro campo que tu tabla 'clientes' pueda tener
    ];

    /**
     * Los atributos que deberían estar ocultos para la serialización.
     * Por lo general, para un cliente, no hay campos sensibles que ocultar como una contraseña.
     * Sin embargo, si tuvieras un campo como 'token_api', lo añadirías aquí.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        // 'token_api', // Ejemplo: si tuvieras un campo de token de API para el cliente
    ];

    /**
     * Obtiene los atributos que deben ser "cast".
     * Puedes usar esto para convertir tipos de datos automáticamente (ej. 'fecha_nacimiento' a 'datetime').
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // 'fecha_nacimiento' => 'datetime', // Ejemplo: si tuvieras un campo de fecha de nacimiento
        ];
    }

    /**
     * Nombre de la tabla asociada con el modelo.
     * Por defecto, Laravel infiere el nombre de la tabla como el plural del nombre del modelo (clientes).
     * Si tu tabla se llama diferente, puedes especificarla aquí:
     * protected $table = 'mis_clientes';
     */
    // protected $table = 'clientes'; // No es necesario si la tabla se llama 'clientes'

    /**
     * La clave primaria de la tabla.
     * Por defecto, Laravel asume 'id'. Si tu clave primaria tiene otro nombre, especifícalo:
     * protected $primaryKey = 'cliente_id';
     */
    // protected $primaryKey = 'id'; // No es necesario si la clave primaria es 'id'

    /**
     * Indica si el modelo debe ser timestamped.
     * Por defecto es true (crea 'created_at' y 'updated_at').
     * Si no usas estos campos en tu tabla, ponlo a false:
     * public $timestamps = false;
     */
    // public $timestamps = true; // No es necesario si usas created_at y updated_at
}
