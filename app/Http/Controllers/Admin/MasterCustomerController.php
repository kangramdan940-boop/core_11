<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterCustomer;
use Illuminate\Http\Request;

class MasterCustomerController extends Controller
{
    public function index()
    {
        $customers = MasterCustomer::orderByDesc('id')->get();

        return view('admin.master_customer.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.master_customer.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name'   => ['required', 'string', 'max:150'],
            'email'       => ['required', 'email', 'max:150', 'unique:master_customer,email'],
            'phone_wa'    => ['required', 'string', 'max:50'],
            'nik'         => ['nullable', 'string', 'max:30'],
            'no_kk'       => ['nullable', 'string', 'max:30'],
            'birth_date'  => ['nullable', 'date'],
            'address_line'=> ['nullable', 'string', 'max:255'],
            'kelurahan'   => ['nullable', 'string', 'max:100'],
            'kecamatan'   => ['nullable', 'string', 'max:100'],
            'kota'        => ['nullable', 'string', 'max:100'],
            'provinsi'    => ['nullable', 'string', 'max:100'],
            'kode_pos'    => ['nullable', 'string', 'max:10'],
            'is_active'   => ['sometimes', 'accepted'],
        ]);
        $data['is_active'] = $request->has('is_active');

        MasterCustomer::create($data);

        return redirect()
            ->route('admin.master.customers.index')
            ->with('success', 'Customer berhasil ditambahkan.');
    }

    public function edit(MasterCustomer $customer)
    {
        return view('admin.master_customer.edit', compact('customer'));
    }

    public function update(Request $request, MasterCustomer $customer)
    {


        $data = $request->validate([
            'full_name'   => ['required', 'string', 'max:150'],
            'email'       => ['required', 'email', 'max:150', 'unique:master_customer,email,' . $customer->id],
            'phone_wa'    => ['required', 'string', 'max:50'],
            'nik'         => ['nullable', 'string', 'max:30'],
            'no_kk'       => ['nullable', 'string', 'max:30'],
            'birth_date'  => ['nullable', 'date'],
            'address_line'=> ['nullable', 'string', 'max:255'],
            'kelurahan'   => ['nullable', 'string', 'max:100'],
            'kecamatan'   => ['nullable', 'string', 'max:100'],
            'kota'        => ['nullable', 'string', 'max:100'],
            'provinsi'    => ['nullable', 'string', 'max:100'],
            'kode_pos'    => ['nullable', 'string', 'max:10'],
            'is_active'   => ['sometimes', 'accepted'],
        ]);

        $data['is_active'] = $request->has('is_active');
        $customer->update($data);

        return redirect()
            ->route('admin.master.customers.index')
            ->with('success', 'Customer berhasil diupdate.');
    }

    public function destroy(MasterCustomer $customer)
    {
        if ($customer->user) {
            return redirect()
                ->route('admin.master.customers.index')
                ->withErrors(['delete' => 'Tidak dapat menghapus customer yang terhubung ke sys_user. Nonaktifkan akun (is_active = false) sebagai gantinya.']);
        }

        $customer->delete();

        return redirect()
            ->route('admin.master.customers.index')
            ->with('success', 'Customer berhasil dihapus.');
    }

    public function setPasswordForm(MasterCustomer $customer)
    {
        $user = \App\Models\User::where('email', $customer->email)->first();
        return view('admin.master_customer.set_password', compact('customer', 'user'));
    }

    public function setPasswordUpdate(Request $request, MasterCustomer $customer)
    {
      $currentUser = \App\Models\User::where('email', $customer->email)->first();

      $data = $request->validate([
        'email' => [
          'required', 'email', 'max:150',
          \Illuminate\Validation\Rule::unique('sys_user', 'email')->ignore($currentUser?->id),
          \Illuminate\Validation\Rule::unique('master_customer', 'email')->ignore($customer->id),
        ],
        'password' => ['required', 'string', 'min:8'],
        'password_confirmation' => ['required', 'same:password'],
      ]);

      $newEmail = $data['email'];

      if (! $currentUser) {
        $user = \App\Models\User::create([
          'name' => $customer->full_name ?? ('Customer ' . $customer->id),
          'email' => $newEmail,
          'password' => $data['password'],
          'role' => 'customer',
          'is_active' => $customer->is_active,
        ]);
        $customer->sys_user_id = $user->id;
      } else {
        $currentUser->email = $newEmail;
        $currentUser->password = $data['password'];
        $currentUser->role = 'customer';
        $currentUser->is_active = $customer->is_active;
        $currentUser->name = $customer->full_name ?? $currentUser->name;
        $currentUser->save();
        $user = $currentUser;
        if (! $customer->sys_user_id) {
          $customer->sys_user_id = $user->id;
        }
      }

      $customer->email = $newEmail;
      $customer->save();

      return redirect()->route('admin.master.customers.index')->with('success', 'Email dan password login customer diperbarui.');
    }
}