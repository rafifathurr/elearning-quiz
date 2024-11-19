<?php

namespace Database\Seeders;

use App\Models\Quiz\QuizAnswer;
use App\Models\Quiz\QuizQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();


            $quiz_question = QuizQuestion::create([
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 1,
                'level' => '|1|2|',
                'aspect' => '|1|2|',
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya akan ikut menyumbang korban gempa lebih besar jika nama saya ditulis dan kalau bisa masuk televisi.',
                'description' => null,
                'time_duration' => 30,
            ]);

            // Jawaban untuk Pertanyaan 1
            QuizAnswer::create(['quiz_question_id' => $quiz_question->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            $quiz_question2 = QuizQuestion::create([
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 2,
                'level' => '|1|2|',
                'aspect' => '|1|2|',
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Di lingkungan manapun saya berada, mudah bagi saya untuk menyesuaikan diri dengan orang-orang yang ada di lingkungan tersebut.',
                'description' => null,
                'time_duration' => 30,
            ]);

            // Jawaban untuk Pertanyaan 2
            QuizAnswer::create(['quiz_question_id' => $quiz_question2->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question2->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question2->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question2->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            $quiz_question3 = QuizQuestion::create([
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 3,
                'level' => '|1|2|',
                'aspect' => '|1|2|',
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya mudah merasa lelah bila diperintah menyelesaikan pekerjaan yang berat.',
                'description' => null,
                'time_duration' => 30,
            ]);

            // Jawaban untuk Pertanyaan 3
            QuizAnswer::create(['quiz_question_id' => $quiz_question3->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question3->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question3->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question3->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 4
            $quiz_question4 = QuizQuestion::create([
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 4,
                'level' => '|2|3|',
                'aspect' => '|1|2|',
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Terkadang saya merasa sakit hati bila ada yang berbicara seenaknya di depan saya.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question4->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question4->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question4->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question4->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 5
            $quiz_question5 = QuizQuestion::create([
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 5,
                'level' => '|2|3|',
                'aspect' => '|1|',
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Ketika marah, saya memilih menceritakan kepada teman sehingga menjadi lebih nyaman.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question5->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question5->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question5->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question5->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 6
            $quiz_question6 = QuizQuestion::create([
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 6,
                'level' => '|2|3|',
                'aspect' => '|2|',
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya tetap menghargai senior meskipun melakukan tindakan yang tidak sesuai dengan ketentuan.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question6->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question6->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question6->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question6->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 7
            $quiz_question7 = QuizQuestion::create([
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 7,
                'level' => '|2|3|',
                'aspect' => '|1|',
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Berbicara di depan orang banyak sama nyamannya dengan berbicara di depan kelas.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question7->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question7->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question7->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question7->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            $quiz_question_type_2_no1 = QuizQuestion::create([
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 1,
                'level' => '|2|3|',
                'aspect' => '|2|',
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Dalam setiap diskusi saya akan:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no1->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Dalam setiap diskusi, saya aktif berpendapat walaupun belum tentu diterima.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no1->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Dalam setiap diskusi saya selalu berusaha meyakinkan kebenaran ide-ide saya', 'attachment' => null]);


            $quiz_question_type_2_no2 = QuizQuestion::create([
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 2,
                'level' => '|2|3|',
                'aspect' => '|2|',
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Dalam menyampaikan informasi penting biasanya saya akan:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no2->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Mengatakan apa adanya meskipun membuat perasaan kurang nyaman', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no2->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Mengatakan dengan menyesuaikan dan mempertimbangkan siapa pendengarnya.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 3
            $quiz_question_type_2_no3 = QuizQuestion::create([
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 3,
                'level' => '|2|3|',
                'aspect' => '|1|2|',
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Apabila ada orang membuat lelucon yang mirip dengan pengalaman saya, maka:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no3->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya akan ikut tertawa karena pengalaman tersebut benar-benar berkesan.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no3->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya merasa kurang pantas jika pengalaman saya dijadikan sebagai lelucon.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 4
            $quiz_question_type_2_no4 = QuizQuestion::create([
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 4,
                'level' => '|1|3|',
                'aspect' => '|2|',
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Apabila ada teman yang menceritakan tentang dirinya, saya akan:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no4->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Mendengarkan seksama sebagai bentuk perhatian saya kepadanya.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no4->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Mendengarkan seksama agar mengetahui keadaan diri teman saya tersebut.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 5
            $quiz_question_type_2_no5 = QuizQuestion::create([
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 5,
                'level' => '|1|3|',
                'aspect' => '|2|',
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Untuk menyelesaikan pekerjaan yang berat, maka:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no5->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya mengatakan akan membuatkan jadwal bergilir.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no5->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya mengatakan akan memberikan insentif bagi anggota.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 6
            $quiz_question_type_2_no6 = QuizQuestion::create([
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 6,
                'level' => '|2|',
                'aspect' => '|2|',
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya mendengarkan saran orang lain',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no6->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Dengarkan saran orang lain, tetapi tetaplah pada konsep sendiri yang sudah pasti.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no6->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Pertimbangkan saran-saran orang lain meski berbeda dengan konsep saya.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 7
            $quiz_question_type_2_no7 = QuizQuestion::create([
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 7,
                'level' => '|2|3|',
                'aspect' => '|1|',
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya akan bercerita rahasia teman',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no7->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Apapun yang terjadi, saya tidak akan menceritakan rahasia teman.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no7->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Dalam keadaan terpaksa saya seminimal mungkin membuka rahasia teman.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 8
            $quiz_question_type_2_no8 = QuizQuestion::create([
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 8,
                'level' => '|2|3|',
                'aspect' => '|1|',
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya mudah mengatakan tidak untuk hal-hal berlawanan dengan nurani saya.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no8->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Tidak sesuai dengan diri saya.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no8->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Sangat sesuai dengan diri saya.', 'attachment' => null]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
