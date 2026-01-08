<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers($search, $perPage)
    {
        return $this->userRepository->getAll($search, $perPage);
    }

    public function createUser(array $data)
    {
        DB::beginTransaction();
        try {
            // Hash password sebelum disimpan
            $data['password'] = Hash::make($data['password']);

            $user = $this->userRepository->create($data);

            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateUser($id, array $data)
    {
        DB::beginTransaction();
        try {
            // Cek apakah password diisi? Jika kosong, hapus dari array agar tidak terupdate jadi null/kosong
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $user = $this->userRepository->update($id, $data);

            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateProfile($id, array $data)
    {
        return $this->userRepository->update($id, $data);
    }

    public function changePassword($id, $newPassword)
    {
        return $this->userRepository->update($id, [
            'password' => \Illuminate\Support\Facades\Hash::make($newPassword)
        ]);
    }

    public function deleteUser($id, $request)
    {
        if ($id == $request->user()->id) {
            throw new \Exception("Anda tidak dapat menghapus akun Anda sendiri.");
        }
        return $this->userRepository->delete($id);
    }
}
