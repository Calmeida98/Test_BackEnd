<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 */
class CreatePersonaTable extends Migration
{

/**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!\Doctrine\DBAL\Types\Type::hasType('char')) {
            \Doctrine\DBAL\Types\Type::addType('char', \Doctrine\DBAL\Types\StringType::class);
        }
        /*Generating tables and columns*/
        $connection =Schema::connection('test');
        $exist_table=$connection->hasTable("persona");
        if(!$exist_table){
            Schema::create("persona",function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->integer('id',true);
                $table->string('nombre',255);
                $table->string('apellidos',255);
                $table->integer('edad');
                $table->string('correo',255);
            });
        }else{
            Schema::table("persona",function (Blueprint $table) {

				/*Validating pk */
                $sm=Schema::connection("test")->getConnection()->getDoctrineSchemaManager();
                $foundpk = true;

				$indexesfound = $sm->listTableIndexes('persona');
                $find = array_filter($indexesfound, function ($element) {
                    return $element->getName() === 'PRIMARY';
                });
                if (count($find) == 0) {
                    $foundpk = false;
                }                
                if(Schema::hasColumn("persona",'id')){
                   $table->integer('id',true)->change();
                }
               else{
                   $table->integer('id',true);
                }                
                if(Schema::hasColumn("persona",'nombre')){
                   $table->string('nombre',255)->change();
                }
               else{
                   $table->string('nombre',255);
                }                
                if(Schema::hasColumn("persona",'apellidos')){
                   $table->string('apellidos',255)->change();
                }
               else{
                   $table->string('apellidos',255);
                }                
                if(Schema::hasColumn("persona",'edad')){
                   $table->integer('edad')->change();
                }
               else{
                   $table->integer('edad');
                }                
                if(Schema::hasColumn("persona",'correo')){
                   $table->string('correo',255)->change();
                }
               else{
                   $table->string('correo',255);
                }            
			});

		  }
            Schema::table("persona",function (Blueprint $table) {

				/*Adding indexes */

				$sm=Schema::connection("test")->getConnection()->getDoctrineSchemaManager();

				$indexesfound = $sm->listTableIndexes('persona');
                foreach ($indexesfound as $el) {
                    if ($el->isUnique() && !$el->isPrimary())
                        $table->dropUnique($el->getName());
                }
                $find = array_filter($indexesfound, function ($element) {
                    return $element->getName() === 'PRIMARY';
                });
				if (count($find)==0)
					$table->primary('id', 'PRIMARY');


            });

    }

   public function down()
    {
        Schema::dropIfExists('persona');


        return false;
    }
}
