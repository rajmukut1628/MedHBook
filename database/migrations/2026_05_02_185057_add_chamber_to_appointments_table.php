public function up()
{
    Schema::table('appointments', function (Blueprint $table) {
        $table->string('chamber_address')->nullable()->after('doctor_id');
    });
}

public function down()
{
    Schema::table('appointments', function (Blueprint $table) {
        $table->dropColumn('chamber_address');
    });
}