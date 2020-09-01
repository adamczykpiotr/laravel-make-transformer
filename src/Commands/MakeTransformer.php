<?php

namespace AdamczykPiotr\MakeTransformer\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MakeTransformer extends GeneratorCommand
{
    /**
     * The command name.
     *
     * @var string
     */
    protected $name = 'make:transformer';

    /**
     * The description.
     *
     * @var string
     */
    protected $description = 'Create a new transformer class';

    /**
     * The type being created.
     *
     * @var string
     */
    protected $type = 'Transformer';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../stubs/transformer.stub';
    }

    /**
     * Get the default namespace for the class
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return "$rootNamespace\Transformers";
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param string $stub
     * @param string $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        //extract Class from ClassTransformer
        $className = Str::before($this->getNameInput(), 'Transformer');
        $variableName = Str::lower($className);
        $tableName = Str::plural($variableName);

        $stub = parent::replaceClass($stub, $className);
        $stub = str_replace('Dummy', $className, $stub);
        $stub = str_replace('$dummy', "$$variableName", $stub);

        //If table exists => obtain all fields & create array content
        if( Schema::hasTable($tableName) ) {

            $values = '';
            foreach( Schema::getColumnListing($tableName) as $index => $column) {
                if($index > 0) $values .= "\t\t\t";
                $values .= "$$variableName->$column => $$variableName->$column,\n";
            }

            //remove ,\n from output
            $values = substr($values, 0, -2);
            $stub = str_replace('//Model fields here', $values, $stub);
        }

        return $stub;
    }
}
