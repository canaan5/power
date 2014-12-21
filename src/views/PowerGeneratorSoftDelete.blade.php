<?php echo "<?php\n"; ?>

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PowerSoftDeleteTable extends Migration {

public function __construct()
{
// Get the prefix
$this->prefix = Config::get('power::prefix', '');
}

/**
* Run the migrations.
*
* @return void
*/
public function up()
{
// Bring to local scope
$prefix = $this->prefix;

// Add soft delete column
Schema::table($prefix.'users', function($table)
{
$table->dateTime('deleted_at')->nullable()->index();
});

$users = DB::table($prefix.'users')
->where('deleted', 1)
->update([
'deleted_at' => date('Y-m-d H:i:s')
]);

Schema::table($prefix.'users', function($table)
{
$table->dropColumn('deleted');
});
}

/**
* Reverse the migrations.
*
* @return void
*/
public function down()
{
// Bring to local scope
$prefix = $this->prefix;

// Add soft delete column
Schema::table($prefix.'users', function($table)
{
$table->boolean('deleted')->default(0);
});

$users = DB::table($prefix.'users')
->whereNotNull('deleted_at')
->update([
'deleted' => 1
]);

Schema::table($prefix.'users', function($table)
{
$table->dropColumn('deleted_at');
});
}

}
