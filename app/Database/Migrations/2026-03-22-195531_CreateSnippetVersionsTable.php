<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSnippetVersionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                       => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'snippet_id'               => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'version_number'           => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'title'                    => [
                'type'       => 'VARCHAR',
                'constraint' => 180,
            ],
            'description'              => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'sql_content'              => [
                'type' => 'LONGTEXT',
            ],
            'database_type_id'         => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'type'                     => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'query',
            ],
            'visibility'               => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'private',
            ],
            'change_note'              => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'is_restore'               => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'restored_from_version_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'created_by'               => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'created_at'               => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('snippet_id');
        $this->forge->addKey('version_number');
        $this->forge->addKey('database_type_id');
        $this->forge->addKey('created_by');
        $this->forge->addKey('is_restore');
        $this->forge->addUniqueKey(['snippet_id', 'version_number']);

        $this->forge->addForeignKey('snippet_id', 'snippets', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('database_type_id', 'database_types', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('restored_from_version_id', 'snippet_versions', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('snippet_versions');
    }

    public function down()
    {
        $this->forge->dropTable('snippet_versions');
    }
}
