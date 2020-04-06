<?php

use Phinx\Migration\AbstractMigration;

class CreateImagesTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('images', ['signed' => false]);
        $table->addColumn('uuid', 'string')
              ->addColumn('created_at', 'datetime', ['null' => true])
              ->addColumn('updated_at', 'datetime', ['null' => true])
              ->create();

        $table->addIndex(['uuid'], [
            'unique' => true
        ])->save();
    }
}
