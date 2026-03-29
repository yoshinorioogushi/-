<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateArticlesTable extends BaseMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/4/en/migrations.html#the-change-method
     *
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('articles');
        $table->addColumn('title', 'string', ['limit' => 255])
              ->addColumn('body', 'text', ['null' => true])
              ->addColumn('user_id', 'integer', ['null' => true])
              ->addColumn('created', 'datetime')
              ->addColumn('modified', 'datetime')
              ->addPrimaryKey('id')
              ->addIndex(['user_id'])
              ->create();
    }
}
