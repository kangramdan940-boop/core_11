<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MasterCustomer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class CustomerAuthApiController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $payload = [
            'name' => (string) ($request->input('fullName') ?? $request->input('name') ?? ''),
            'email' => (string) ($request->input('email') ?? ''),
            'password' => (string) ($request->input('password') ?? ''),
            'password_confirmation' => (string) ($request->input('confirmPassword') ?? $request->input('password_confirmation') ?? ''),
            'phone_wa' => $request->filled('phone') ? (string) $request->input('phone') : ($request->filled('phone_wa') ? (string) $request->input('phone_wa') : null),
            'address_line' => null,
        ];

        $validator = Validator::make($payload, [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:sys_user,email'],
            'password' => ['required', 'min:6', 'confirmed'],
            'password_confirmation' => ['required'],
            'phone_wa' => ['nullable', 'string', 'max:30', 'unique:master_customer,phone_wa'],
            'address_line' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'meta' => ['requestId' => (string) Str::uuid()],
            ], 422);
        }

        $data = $validator->validated();

        try {
            $user = DB::transaction(function () use ($data) {
                $user = new User([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'role' => 'customer',
                    'is_active' => true,
                ]);
                $user->save();

                MasterCustomer::create([
                    'full_name' => $data['name'],
                    'email' => $data['email'],
                    'phone_wa' => $data['phone_wa'] ?? null,
                    'address_line' => $data['address_line'] ?? null,
                    'is_active' => true,
                    'sys_user_id' => $user->id,
                ]);

                return $user;
            });

            if (! $user instanceof User) {
                throw new \RuntimeException('Registrasi gagal membuat user.');
            }

            $token = $user->createToken('mobile')->plainTextToken;

            return response()->json([
                'status' => true,
                'data' => [
                    'token' => $token,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                    ],
                    'customer' => MasterCustomer::where('sys_user_id', $user->id)->first([
                        'id', 'full_name', 'email', 'phone_wa', 'address_line', 'is_active'
                    ]),
                ],
                'meta' => [
                    'createdAt' => now()->toIso8601String(),
                    'requestId' => (string) Str::uuid(),
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'errors' => ['general' => ['Gagal membuat akun.']],
                'meta' => ['requestId' => (string) Str::uuid()],
            ], 500);
        }
    }

    public function login(Request $request): JsonResponse
    {
        $payload = [
            'email' => (string) ($request->input('email') ?? ''),
            'password' => (string) ($request->input('password') ?? ''),
        ];

        $validator = Validator::make($payload, [
            'email' => ['required', 'email', 'max:150'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'meta' => ['requestId' => (string) Str::uuid()],
            ], 422);
        }

        $data = $validator->validated();

        $user = User::where('email', $data['email'])->first();

        if (! $user || $user->role !== 'customer' || ! $user->is_active || ! Hash::check($data['password'], $user->password)) {
            return response()->json([
                'status' => false,
                'errors' => ['email' => ['Email atau password salah atau akun tidak aktif.']],
                'meta' => ['requestId' => (string) Str::uuid()],
            ], 401);
        }

        $user->forceFill(['last_login_at' => now()])->save();

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'status' => true,
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'customer' => MasterCustomer::where('sys_user_id', $user->id)->first([
                    'id', 'full_name', 'email', 'phone_wa', 'address_line', 'is_active'
                ]),
            ],
            'meta' => [
                'loggedInAt' => now()->toIso8601String(),
                'requestId' => (string) Str::uuid(),
            ],
        ]);
    }
}