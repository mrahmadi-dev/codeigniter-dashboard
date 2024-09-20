<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class AddUser extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '128',
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '128',
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => '128',
            ],
            'verified' => [
                'type' => 'TINYINT',
                'default' => 0
            ],
            'verify_token' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'refresh_password_token' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true
            ],
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true
            ],
            'phone_number' => [
                'type' => 'VARCHAR',
                'constraint' => '11',
                'null' => true
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'language' => [
                'type' => 'ENUM("en","fa","fr","de","pt")',
                'default' => 'en',
                'null' => false
            ],
            'currency' => [
                'type' => 'ENUM("usd","rial","euro","pound","bitcoin")',
                'default' => 'usd',
                'null' => false
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'default' => 1
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
