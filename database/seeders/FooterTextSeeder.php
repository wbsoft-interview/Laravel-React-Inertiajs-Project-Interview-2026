<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\FooterText;

class FooterTextSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $text = FooterText::create([
            'user_id' => 1, 
            'text' => 'test text', 
            'solid_text' => 'test text',
        ]);
    }
}
