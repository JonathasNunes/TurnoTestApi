<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    protected $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function create(array $data)
    {
        return $this->userModel->create($data);
    }

    public function findById($id)
    {
        return $this->userModel->find($id);
    }

    public function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function update($id, $data)
    {
        $user = $this->findById($id);

        if ($user) {
            $user->update($data);
            return $user;
        }

        return null;
    }

    public function delete($id)
    {
        $user = $this->findById($id);

        if ($user) {
            $user->delete();
            return true;
        }

        return false;
    }
}