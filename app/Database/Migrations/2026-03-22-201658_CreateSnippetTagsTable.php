<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSnippetTagsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'snippet_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'tag_id'     => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('snippet_id');
        $this->forge->addKey('tag_id');
        $this->forge->addUniqueKey(['snippet_id', 'tag_id']);

        $this->forge->addForeignKey('snippet_id', 'snippets', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tag_id', 'tags', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('snippet_tags');
    }

    public function down()
    {
        $this->forge->dropTable('snippet_tags');
    }
}
