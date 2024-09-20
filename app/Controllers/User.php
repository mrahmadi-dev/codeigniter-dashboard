<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Files\File;
use Config\Database;
use Config\Services;
use UserModel;

class User extends BaseController
{
    public function profile()
    {
        $userID = session()->get('id');
        $userModel = model(UserModel::class);
        $profile = $userModel->where('id', $userID)->first();
        if ($this->request->is('get')) {
            $profile['userImage'] = $profile['image'] ? 'uploads/profiles/'.$profile['image'] : 'assets/img/avatars/1.png';
            return view('admin/profile', $profile);
        } else {
            $action = $this->request->getPost('acc');
            if ($action === 'updateProfile') {
                return $this->updateProfile($userID,$profile);
            } elseif ($action === 'updateProfileImage') {
                return $this->updateProfileImage($userID,$profile);
            }
        }
    }

    public function updateProfileImage($userID,$profile)
    {
        helper('form');
        $validationRule = [
            'img' => [
                'label' => 'Image File',
                'rules' => [
                    'uploaded[img]',
                    'is_image[img]',
                    'mime_in[img,image/jpg,image/jpeg,image/gif,image/png]',
                    'max_size[img,100]',
                    'max_dims[img,1024,768]',
                ],
            ],
        ];
        if (!$this->validate($validationRule)) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => $this->validator->getErrors(),'hash' => csrf_hash()]);
        }

        $img = $this->request->getFile('img');

        $db = Database::connect();
        $user = $db->table('users')->where('id', $userID);


        if (!$img->hasMoved()) {

            // Get random file name
            $newName = $img->getRandomName();

            // Store file in public folder
            $img->move('../public/uploads/profiles', $newName);

            if ($profile['image']) {
                unlink('../public/uploads/profiles/'.$profile['image'] );
            }

            $user->update([
                'image' => $newName
            ]);

            session()->set([
                'image' => $newName
            ]);
            return $this->response->setStatusCode(200)->setJSON(['status' => 'ok', 'message' => 'uploaded', 'data' => base_url().'uploads/profiles/'.$newName,'hash'=>csrf_hash()]);
        }
        return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'The file has already been moved.', 'hash' => csrf_hash()]);
    }

    public function updateProfile($userID,$profile)
    {
        helper('form');

        $data = $this->request->getPost();

        if (! $this->validateData($data,[
            'name'  => 'required',
            'phoneNumber' => 'max_length[11]|numeric|permit_empty'
        ])) {
            return $this->response->setStatusCode(400)->setJSON(['s'=>validation_list_errors(),'ss'=>csrf_hash()]);
        }


        $db = Database::connect();
        $db->table('users')->where('id',$userID)->update([
            'name' => $data['name'],
            'phone_number' => $data['phoneNumber'],
            'address' => $data['address'],
            'language' => $data['language'],
            'currency' => $data['currency']
        ]);
        return $this->response->setStatusCode(200)->setJSON([]);
    }

    public function deactivateAccount()
    {
        $userID = session()->get('id');
        $db = Database::connect();
        $db->table('users')->where('id',$userID)->update([
            'isActive' => 0
        ]);
        return redirect('auth/logout');
    }
}
