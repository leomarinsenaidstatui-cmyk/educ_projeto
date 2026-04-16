<?php

namespace App\Http\Middleware;

use App\Models\TokenUser;
use App\Models\Usuario;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthApi
{
    public function handle(Request $request, Closure $next): Response
    {
        $tokenValor = $request->input('token');

        if (! $tokenValor) {
            $bearerToken = $request->bearerToken();
            if ($bearerToken) {
                $tokenValor = $bearerToken;
            }
        }

        if (! $tokenValor) {
            return response()->json([
                'erro' => 's',
                'msg' => 'Voce nao enviou o token.',
            ], 401);
        }

        $token = TokenUser::where('token', $tokenValor)
            ->where('valido_ate', '>=', Carbon::now())
            ->first();

        if (! $token) {
            return response()->json([
                'erro' => 's',
                'msg' => 'Token invalido ou expirado.',
            ], 401);
        }

        $usuario = Usuario::find($token->user_id);
        if (! $usuario) {
            return response()->json([
                'erro' => 's',
                'msg' => 'Usuario nao encontrado para o token informado.',
            ], 401);
        }

        $request->attributes->set('usuario', $usuario);
        $request->attributes->set('token_user', $token);

        return $next($request);
    }
}

