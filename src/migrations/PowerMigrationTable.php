<?php

use Illuminate\Database\Migrations\Migration;

class PowerMigrationTable extends Migration
{

    public function __construct()
    {
        // Get the prefix
        $this->prefix = Config::get('power::prefix', '');
    }

    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        // Bring to local scope
        $prefix = $this->prefix;

        // Create the permissions table
        Schema::create($prefix . 'permissions', function ($table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('name', 100)->index();
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });

        // Create the roles table
        Schema::create($prefix . 'groups', function ($table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('name', 100)->index();
            $table->string('description', 255)->nullable();
            $table->integer('level');
            $table->timestamps();
        });

        // Create the users table
        Schema::create($prefix . 'users', function ($table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('firstName');
            $table->string('lastName');
            $table->string('username', 30)->index()->unique();
            $table->string('password', 60)->index();
            $table->string('salt', 32);
            $table->string('email', 255)->index()->unique();
            $table->string('remember_token', 100)->nullable()->index();
            $table->boolean('verified')->default(0);
            $table->boolean('disabled')->default(0);
            $table->boolean('deleted')->default(0);
            $table->timestamps();
        });

        // Create the role/user relationship table
        Schema::create($prefix . 'groupUser', function ($table) use ($prefix) {
            $table->engine = 'InnoDB';

            $table->integer('userId')->unsigned()->index();
            $table->integer('groupId')->unsigned()->index();
            $table->timestamps();

            $table->foreign('userId')->references('id')->on($prefix . 'users')->onDelete('cascade');
            $table->foreign('groupId')->references('id')->on($prefix . 'groups')->onDelete('cascade');
        });

        // Create the permission/Group relationship table
        Schema::create($prefix . 'permissionGroup', function ($table) use ($prefix) {
            $table->engine = 'InnoDB';

            $table->integer('permissionId')->unsigned()->index();
            $table->integer('groupId')->unsigned()->index();
            $table->timestamps();

            $table->foreign('permissionId')->references('id')->on($prefix . 'permissions')->onDelete('cascade');
            $table->foreign('groupId')->references('id')->on($prefix . 'groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::drop($this->prefix . 'groupUser');
        Schema::drop($this->prefix . 'permissionGroup');
        Schema::drop($this->prefix . 'users');
        Schema::drop($this->prefix . 'groups');
        Schema::drop($this->prefix . 'permissions');
    }
}
