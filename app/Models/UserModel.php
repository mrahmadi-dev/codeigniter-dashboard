<?php


use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $allowedFields = [
        'name',
        'email',
        'password',
        'token',
        'image',
        'verify_token',
        'refresh_password_token'
    ];
}