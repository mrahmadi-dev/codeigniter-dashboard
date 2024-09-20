<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Database;
use Config\Services;
use UserModel;

class Auth extends BaseController
{
    public function login()
    {
        helper('form');
        if ($this->request->is('get')) {
            if (session()->get('id')) {
                return redirect()->to('/admin');
            } else {
                return view('auth/login');
            }
        }

        $data = $this->request->getPost(['email', 'password']);

        // Checks whether the submitted data passed the validation rules.
        if (!$this->validateData($data, [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]',
        ])) {
            return view('auth/login');
        }

        $userModel = model(UserModel::class);
        $user = $userModel->where('email', $data['email'])->where('is_active',1)->first();

        if ($user) {
            if (password_verify($data['password'], $user['password'])) {
                session()->set([
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'verified' => $user['verified'],
                    'image' => $user['image']
                ]);
                return redirect('admin');
            } else {
                session()->setFlashdata('message', '* Password is incorrect');
                return redirect()->to('auth/login');
            }
        } else {
            session()->setFlashdata('message', '* Email does not exist');
            return redirect()->to('auth/login');
        }

    }

    /**
     * @throws \Exception
     */
    public function register()
    {
        helper('form');

        if ($this->request->is('get')) {
            if (session()->get('id')) {
                return redirect()->to('/admin');
            } else {
                return view('auth/register');
            }
        }

        $data = $this->request->getPost();

        // Checks whether the submitted data passed the validation rules.
        if (!$this->validateData($data, [
            'name' => 'required',
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]',
        ])) {
            return view('auth/register');
        }

        $userModel = model(UserModel::class);
        $user = $userModel->where('email', $data['email'])->first();

        if (!$user) {
            $token = bin2hex(random_bytes(30));
            $userModel->save([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'verify_token' => $token
            ]);
            $this->sendVerificationEmail($token,$data['email']);
            session()->setFlashdata('registration','Your account has been created.You can log in now');
            return redirect()->to('auth/login');

        } else {
            session()->setFlashdata('message', '* You have already registered');
            return redirect()->to('auth/register');
        }
    }

    public function logout()
    {
        session()->remove('id');
        return redirect()->to('auth/login');
    }

    public function sendVerificationEmail($token,$enteredEmail)
    {
        $email = \Config\Services::email();

        $email->setFrom('moho@mrahmadi.com', 'karia');
        $email->setTo($enteredEmail);
//        $email->setCC('another@another-example.com');
//        $email->setBCC('them@their-example.com');

        $parser = Services::renderer();
        $body = $parser->setData([
            'verify_token' => $token,
        ])->render('templates/verifyEmail_template');

        $email->setSubject('Confirm Your email address');
        $email->setMessage($body);
        $email->send();
    }

    public function sendForgetPasswordEmail($token,$enteredEmail)
    {
        $email = \Config\Services::email();

        $email->setFrom('moho@mrahmadi.com', 'karia');
        $email->setTo($enteredEmail);

        $parser = Services::renderer();
        $body = $parser->setData([
            'verify_token' => $token,
        ])->render('templates/forgetPasswordEmail_template');

        $email->setSubject('Refresh your password');
        $email->setMessage($body);
        return $email->send();
    }

    public function verifyEmail($token)
    {
        $db = Database::connect();
        $user = $db->table('users')->where('verify_token',$token)->get();

        if ($user) {
            $db->table('users')->where('verify_token',$token)->update([
                'verified' => 1
            ]);
            $output = [
                'verified' => true,
            ];
            session()->set([
                'verified' => 1
            ]);
        }else {
            $output = [
                'verified' => false,
            ];
        }

        return view('auth/emailVerification',$output);
    }

    public function forgetPassword()
    {
        helper('form');
        if ($this->request->is('get')) {
            return view('auth/forgetPassword',['status'=>'success']);
        }

        $enteredEmail = $this->request->getPost('email');

        $db = Database::connect();
        $builder = $db->table('users');
        $user = $builder->select('*')->where('email',$enteredEmail)->get()->getResult()[0];


        if ($user) {
            if (!$user->verify_token) {
                session()->setFlashdata('message', '* To recover your password, you must first confirm your email');
                return view('auth/forgetPassword',['status'=>'danger']);
            }
            $refreshPasswordToken = bin2hex(random_bytes(30));
            $updateUser =$db->table('users')->where('email',$enteredEmail)->update([
                'refresh_password_token' => $refreshPasswordToken
            ]);
            if ($updateUser) {
                $email = $this->sendForgetPasswordEmail($refreshPasswordToken,$enteredEmail);
                session()->setFlashdata('message', '* The recovery link has been sent to your email');
                return view('auth/forgetPassword',['status'=>'success']);
            }

        }
        session()->setFlashdata('message', '* The email entered is not valid');
        return view('auth/forgetPassword',['status'=>'danger']);
    }

    public function changePassword($token)
    {
        helper('form');
        $db = Database::connect();
        $builder = $db->table('users');
        $user = $builder->select('id')->where('refresh_password_token',$token)->get()->getResult();

        if ($this->request->is('get')) {
            if ($user) {
                return view('auth/changePassword');
            }
        }

        $data = $this->request->getPost();

        if (!$this->validateData($data, [
            'password' => 'required|min_length[10]',
            'rePassword' => 'required|matches[password]',
        ])) {
            return view('auth/changePassword');
        }

        $builder->update([
            'password' => password_hash($data['password'],PASSWORD_DEFAULT)
        ]);

        session()->setFlashdata('mess', '* Your password has been changed !');
        return view('auth/changePassword',['status'=>'success']);
    }
}
