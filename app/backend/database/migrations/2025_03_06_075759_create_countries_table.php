<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->char('code', 2)->unique();
            $table->string('name', 100);
            $table->timestamps();
        });

        // Insert country data
        DB::table('countries')->insert([
            ['code' => 'AF', 'name' => 'Afghanistan'],
            ['code' => 'AL', 'name' => 'Albania'],
            ['code' => 'DZ', 'name' => 'Algeria'],
            ['code' => 'AD', 'name' => 'Andorra'],
            ['code' => 'AO', 'name' => 'Angola'],
            ['code' => 'AR', 'name' => 'Argentina'],
            ['code' => 'AM', 'name' => 'Armenia'],
            ['code' => 'AU', 'name' => 'Australia'],
            ['code' => 'AT', 'name' => 'Austria'],
            ['code' => 'AZ', 'name' => 'Azerbaijan'],
            ['code' => 'BS', 'name' => 'Bahamas'],
            ['code' => 'BH', 'name' => 'Bahrain'],
            ['code' => 'BD', 'name' => 'Bangladesh'],
            ['code' => 'BB', 'name' => 'Barbados'],
            ['code' => 'BY', 'name' => 'Belarus'],
            ['code' => 'BE', 'name' => 'Belgium'],
            ['code' => 'BZ', 'name' => 'Belize'],
            ['code' => 'BJ', 'name' => 'Benin'],
            ['code' => 'BT', 'name' => 'Bhutan'],
            ['code' => 'BO', 'name' => 'Bolivia'],
            ['code' => 'BA', 'name' => 'Bosnia and Herzegovina'],
            ['code' => 'BW', 'name' => 'Botswana'],
            ['code' => 'BR', 'name' => 'Brazil'],
            ['code' => 'BG', 'name' => 'Bulgaria'],
            ['code' => 'BF', 'name' => 'Burkina Faso'],
            ['code' => 'BI', 'name' => 'Burundi'],
            ['code' => 'KH', 'name' => 'Cambodia'],
            ['code' => 'CM', 'name' => 'Cameroon'],
            ['code' => 'CA', 'name' => 'Canada'],
            ['code' => 'TD', 'name' => 'Chad'],
            ['code' => 'CL', 'name' => 'Chile'],
            ['code' => 'CN', 'name' => 'China'],
            ['code' => 'CO', 'name' => 'Colombia'],
            ['code' => 'CR', 'name' => 'Costa Rica'],
            ['code' => 'HR', 'name' => 'Croatia'],
            ['code' => 'CU', 'name' => 'Cuba'],
            ['code' => 'CY', 'name' => 'Cyprus'],
            ['code' => 'CZ', 'name' => 'Czech Republic'],
            ['code' => 'DK', 'name' => 'Denmark'],
            ['code' => 'DO', 'name' => 'Dominican Republic'],
            ['code' => 'EC', 'name' => 'Ecuador'],
            ['code' => 'EG', 'name' => 'Egypt'],
            ['code' => 'FI', 'name' => 'Finland'],
            ['code' => 'FR', 'name' => 'France'],
            ['code' => 'DE', 'name' => 'Germany'],
            ['code' => 'GR', 'name' => 'Greece'],
            ['code' => 'IN', 'name' => 'India'],
            ['code' => 'ID', 'name' => 'Indonesia'],
            ['code' => 'IE', 'name' => 'Ireland'],
            ['code' => 'IT', 'name' => 'Italy'],
            ['code' => 'JP', 'name' => 'Japan'],
            ['code' => 'KE', 'name' => 'Kenya'],
            ['code' => 'KR', 'name' => 'South Korea'],
            ['code' => 'KW', 'name' => 'Kuwait'],
            ['code' => 'LV', 'name' => 'Latvia'],
            ['code' => 'LB', 'name' => 'Lebanon'],
            ['code' => 'LT', 'name' => 'Lithuania'],
            ['code' => 'MY', 'name' => 'Malaysia'],
            ['code' => 'MX', 'name' => 'Mexico'],
            ['code' => 'NG', 'name' => 'Nigeria'],
            ['code' => 'NO', 'name' => 'Norway'],
            ['code' => 'PK', 'name' => 'Pakistan'],
            ['code' => 'PH', 'name' => 'Philippines'],
            ['code' => 'PL', 'name' => 'Poland'],
            ['code' => 'PT', 'name' => 'Portugal'],
            ['code' => 'RO', 'name' => 'Romania'],
            ['code' => 'RU', 'name' => 'Russia'],
            ['code' => 'SA', 'name' => 'Saudi Arabia'],
            ['code' => 'SG', 'name' => 'Singapore'],
            ['code' => 'ES', 'name' => 'Spain'],
            ['code' => 'SE', 'name' => 'Sweden'],
            ['code' => 'CH', 'name' => 'Switzerland'],
            ['code' => 'AE', 'name' => 'United Arab Emirates'],
            ['code' => 'GB', 'name' => 'United Kingdom'],
            ['code' => 'US', 'name' => 'United States'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
