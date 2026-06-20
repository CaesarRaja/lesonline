<?php

namespace Database\Seeders;

use App\Models\Mentor;
use App\Models\Schedule;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin BimbelEdu',
            'email' => 'admin@bimbeledu.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $student = User::create([
            'name' => 'Budi Santoso',
            'email' => 'student@bimbeledu.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);

        $mentorUser = User::create([
            'name' => 'Sarah Wijaya',
            'email' => 'mentor@bimbeledu.com',
            'password' => bcrypt('password'),
            'role' => 'mentor',
        ]);

        $mentor = Mentor::create([
            'user_id' => $mentorUser->id,
            'bio' => 'Certified ESL Instructor dengan pengalaman 8 tahun mengajar TOEFL dan IELTS. Pendekatan belajar yang interaktif dan menyenangkan.',
            'tarif_per_jam' => 120000,
            'link_meeting' => 'https://meet.google.com/abc-defg-hij',
            'rating_rata_rata' => 4.9,
            'keahlian' => 'TOEFL, IELTS, Speaking',
        ]);

        $mentor2User = User::create([
            'name' => 'Budi Santoso',
            'email' => 'mentor2@bimbeledu.com',
            'password' => bcrypt('password'),
            'role' => 'mentor',
        ]);

        $mentor2 = Mentor::create([
            'user_id' => $mentor2User->id,
            'bio' => 'Senior Backend Engineer dengan pengalaman 5+ tahun di Python, Django, dan SQL.',
            'tarif_per_jam' => 150000,
            'link_meeting' => 'https://meet.google.com/xyz-uvwx-yz',
            'rating_rata_rata' => 4.8,
            'keahlian' => 'Python, Django, SQL',
        ]);

        $mentor3User = User::create([
            'name' => 'Reza Pahlevi',
            'email' => 'mentor3@bimbeledu.com',
            'password' => bcrypt('password'),
            'role' => 'mentor',
        ]);

        $mentor3 = Mentor::create([
            'user_id' => $mentor3User->id,
            'bio' => 'Product Design Lead dengan spesialisasi UI/UX, Figma, dan prototyping.',
            'tarif_per_jam' => 200000,
            'link_meeting' => 'https://meet.google.com/123-456-789',
            'rating_rata_rata' => 4.7,
            'keahlian' => 'UI/UX, Figma, Prototyping',
        ]);
    }
}
