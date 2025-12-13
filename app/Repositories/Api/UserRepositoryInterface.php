<?php

namespace App\Repositories\Api;

interface UserRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function attemptLogin(array $credentials, $remember = false);
    public function attemptApiLogin(array $credentials);
}

