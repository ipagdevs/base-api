<?php

namespace App\Models;

use App\Services\User\UserApiTokenService;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    const USERNAME = 'login';
    const PASSWORD = 'api_token';

    protected $table = 'pessoa';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function apiTokens()
    {
        return $this->hasMany(UserApiToken::class, 'user_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'idPessoa');
    }

    public function generateApiToken()
    {
        return (new UserApiTokenService())->generate($this);
    }

    public function methods()
    {
        return $this->belongsToMany(Method::class, 'metodopessoa', 'idPessoa', 'idMetodo')
            ->withPivot('id',
                'titulo', 'status', 'modo', 'afiliacao', 'chave', 'usuarioCaptura',
                'senhaCaptura', 'banco', 'agencia', 'dvAgencia', 'conta', 'dvConta',
                'carteira', 'convenio', 'prazo', 'instrucoes', 'taxa', 'juros', 'multa',
                'parcelas', 'autenticacao', 'tipoparcelamento', 'capturaautomatica',
                'avs', 'autorizacao', 'codigocedente', 'dvcodigocedente', 'inicionossonumero',
                'urllogomarca', 'prioridade', 'tentativas', 'outrometodo', 'valorminimo',
                'valormaximo', 'maxparcela', 'chave_vencimento'
            )
            ->withTimestamps();
    }
}
