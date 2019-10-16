<?php

namespace Ezadev\Admin\Scaffold\Scaffold;

use Illuminate\Database\Migrations\MigrationCreator as BaseMigrationCreator;
use Illuminate\Support\Arr;

class MigrationCreator extends BaseMigrationCreator
{

    protected $bluePrint = '';

    public function create($name, $path, $table = null, $create = true)
    {
        $this->ensureMigrationDoesntAlreadyExist($name);

        $path = $this->getPath($name, $path);

        $stub = $this->files->get(__DIR__.'/stubs/create.stub');

        $this->files->put($path, $this->populateStub($name, $stub, $table));

        $this->firePostCreateHooks($table);

        return $path;
    }

    protected function populateStub($name, $stub, $table)
    {
        return str_replace(
            ['DummyClass', 'DummyTable', 'DummyStructure'],
            [$this->getClassName($name), $table, $this->bluePrint],
            $stub
        );
    }

    public function buildBluePrint($fields = [], $keyName = 'id', $useTimestamps = true, $softDeletes = false)
    {
        $fields = array_filter($fields, function ($field) {
            return isset($field['name']) && !empty($field['name']);
        });

        if (empty($fields)) {
            throw new \Exception('Table fields can\'t be empty');
        }

        $rows[] = "\$table->increments('$keyName');\n";

        foreach ($fields as $field) {
            $column = "\$table->{$field['type']}('{$field['name']}')";

            if ($field['key']) {
                $column .= "->{$field['key']}()";
            }

            if (isset($field['default']) && $field['default']) {
                $column .= "->default('{$field['default']}')";
            }

            if (isset($field['comment']) && $field['comment']) {
                $column .= "->comment('{$field['comment']}')";
            }

            if (Arr::get($field, 'nullable') == 'on') {
                $column .= '->nullable()';
            }

            $rows[] = $column.";\n";
        }

        if ($useTimestamps) {
            $rows[] = "\$table->timestamps();\n";
        }

        if ($softDeletes) {
            $rows[] = "\$table->softDeletes();\n";
        }

        $this->bluePrint = trim(implode(str_repeat(' ', 12), $rows), "\n");

        return $this;
    }
}
