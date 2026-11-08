<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

echo "Memeriksa tabel theme...\n";

if (!Schema::hasTable('theme')) {
    Schema::create('theme', function (Blueprint $table) {
        $table->id();
        $table->string('logo')->nullable();
        $table->string('longlogo')->nullable();
        $table->string('favicon')->nullable();
        $table->string('title')->nullable();
        $table->text('discription')->nullable();
        $table->string('metacolor')->nullable();
        
        // Warna Light Mode
        $table->string('highlightcolor')->nullable();
        $table->string('lightcolor')->nullable();
        $table->string('color')->nullable();
        $table->string('subcolor')->nullable();
        $table->string('color4')->nullable();
        $table->string('color5')->nullable();
        $table->string('color6')->nullable();
        $table->string('dark')->nullable();
        $table->string('background')->nullable();
        $table->string('input')->nullable();
        $table->string('primary')->nullable();
        $table->string('secondary')->nullable();
        
        // Warna Dark Mode
        $table->string('lhighlightcolor')->nullable();
        $table->string('llightcolor')->nullable();
        $table->string('lcolor')->nullable();
        $table->string('lsubcolor')->nullable();
        $table->string('lcolor4')->nullable();
        $table->string('lcolor5')->nullable();
        $table->string('lcolor6')->nullable();
        $table->string('ldark')->nullable();
        $table->string('lbackground')->nullable();
        $table->string('linput')->nullable();
        $table->string('lprimary')->nullable();
        $table->string('lsecondary')->nullable();
        
        // Tombol & UI
        $table->string('primaryhover')->nullable();
        $table->string('secondaryhover')->nullable();
        $table->string('dangerbutton')->nullable();
        $table->string('dangerbuttonhover')->nullable();
        $table->string('textbutton')->nullable();
        $table->string('textbuttonhover')->nullable();
        $table->string('lprimaryhover')->nullable();
        $table->string('lsecondaryhover')->nullable();
        $table->string('ldangerbutton')->nullable();
        $table->string('ldangerbuttonhover')->nullable();
        $table->string('ltextbutton')->nullable();
        $table->string('ltextbuttonhover')->nullable();
        $table->string('borderradius')->nullable();
        
        // Sosial & Layout
        $table->string('website')->nullable();
        $table->string('discord')->nullable();
        $table->string('billing')->nullable();
        $table->string('status')->nullable();
        $table->string('knowledgebase')->nullable();
        $table->string('newserverlist')->nullable();
        $table->string('sidegraph')->nullable();
        $table->string('subnavigation')->nullable();
        $table->string('infoelement')->nullable();
        $table->string('graphcard')->nullable();
        $table->string('typealert')->nullable();
        $table->text('alertdescription')->nullable();
        $table->string('backgroundimage')->nullable();
        $table->string('backgroundimage_dark')->nullable();
        $table->string('background_mode')->nullable();
        
        $table->timestamps();
    });
    echo "✅ Tabel theme berhasil dibuat.\n";
} else {
    echo "Tabel theme sudah ada, memeriksa kolom...\n";
    
    // Daftar semua kolom yang harus ada
    $columns = [
        'longlogo' => 'string', 'favicon' => 'string', 'typealert' => 'string', 
        'alertdescription' => 'text', 'backgroundimage' => 'string', 
        'backgroundimage_dark' => 'string', 'background_mode' => 'string'
    ];

    foreach ($columns as $col => $type) {
        if (!Schema::hasColumn('theme', $col)) {
            Schema::table('theme', function (Blueprint $table) use ($col, $type) {
                if ($type === 'text') {
                    $table->text($col)->nullable();
                } else {
                    $table->string($col)->nullable();
                }
            });
            echo "  + Kolom $col ditambahkan.\n";
        }
    }
}

// Isi data default jika tabel kosong
if (DB::table('theme')->count() === 0) {
    DB::table('theme')->insert([
        'title' => 'Pterodactyl Panel',
        'logo' => '/assets/images/pterodactyl.svg',
        'longlogo' => '/assets/images/pterodactyl.svg',
        'highlightcolor' => '#ffd700',
        'background' => '#f4f4f0',
        'primary' => '#ff90e8',
        'secondary' => '#90ee90',
        'website' => 'none',
        'discord' => 'none',
        'status' => 'none',
        'billing' => 'none',
        'knowledgebase' => 'none',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "✅ Data default berhasil dimasukkan.\n";
}

echo "✅ Database siap digunakan!\n";
