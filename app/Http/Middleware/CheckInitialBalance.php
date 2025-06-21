<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckInitialBalance
{
    public function handle(Request $request, Closure $next)
    {
        $initialBalance = DB::table('cash')->count();

        if ($initialBalance == 0) {
            $role = \App\Helpers\UserHelper::userHasPermission(['finance']);
            if(!$role) {
                $sweetalert = [
                    'state' => "error",
                    'title' => "Akses Ditolak",
                    'message' => "Saldo kas awal belum diatur",
                ];
                return redirect('/dashboard')->with('sweetalert', $sweetalert);
            }

            $sweetalert = [
                'state' => "error",
                'title' => "Saldo Kas Awal Belum Diatur",
                'message' => "Anda harus mengatur saldo awal kas terlebih dahulu!",
            ];

            return redirect('/keuangan/kas-awal')->with('sweetalert', $sweetalert);
        }

        return $next($request);
    }
}

