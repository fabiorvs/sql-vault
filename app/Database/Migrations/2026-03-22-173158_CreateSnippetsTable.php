<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSnippetsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id'          => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'database_type_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'type'             => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'query',
                'comment'    => 'query, trigger, procedure, function, view, script',
            ],
            'title'            => [
                'type'       => 'VARCHAR',
                'constraint' => 180,
            ],
            'description'      => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'sql_content'      => [
                'type' => 'LONGTEXT',
            ],
            'visibility'       => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'private',
                'comment'    => 'private, shared',
            ],
            'created_at'       => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at'       => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at'       => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('database_type_id');
        $this->forge->addKey('type');
        $this->forge->addKey('visibility');

        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('database_type_id', 'database_types', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('snippets');
    }

    public function down()
    {
        $this->forge->dropTable('snippets');
    }
}
