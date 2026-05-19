<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Middleware RBAC sederhana.
     *
     * Penggunaan di route:
     *   ->middleware('role:admin')
     *   ->middleware('role:admin,supervisor')
     *   ->middleware('role:safety officer')   ← spasi dihandle
     *   ->middleware('role:safety_officer')   ← underscore juga dihandle
     *
     * Normalisasi yang dilakukan:
     *   - Trim & lowercase
     *   - Underscore → spasi ("safety_officer" → "safety officer")
     *
     * Sehingga kolom `role` di DB cukup menyimpan "safety officer"
     * dan middleware bisa menerima input variasi apapun.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek login
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Normalisasi role user dari DB
        $userRole = $this->normalize($user->role ?? '');

        // Normalisasi setiap role yang diizinkan
        $allowedRoles = array_map(fn ($r) => $this->normalize($r), $roles);

        if (!in_array($userRole, $allowedRoles)) {
            // Redirect ke dashboard sesuai role-nya, bukan ke login
            return redirect($this->dashboardUrl($userRole))
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }

    /**
     * Normalisasi string role: trim, lowercase, underscore → spasi.
     */
    private function normalize(string $role): string
    {
        return str_replace('_', ' ', strtolower(trim($role)));
    }

    /**
     * Tentukan URL dashboard berdasarkan role.
     */
    private function dashboardUrl(string $normalizedRole): string
    {
        return match ($normalizedRole) {
            'admin'          => '/admin/dashboard',
            'pekerja'        => '/pekerja/dashboard',
            'supervisor'     => '/supervisor/dashboard',
            'safety officer' => '/safety-officer/dashboard',
            default          => '/login',
        };
    }
}