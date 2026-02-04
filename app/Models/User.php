<?php
declare(strict_types=1);
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasRoles;
    protected $fillable = ['name','email','password'];
    protected $hidden = ['password'];
    public function getJWTIdentifier(): mixed { return $this->getKey(); }
    public function getJWTCustomClaims(): array { return ['roles' => $this->getRoleNames()]; }
}
