<?php

namespace Database\Seeders;

use App\Models\Quiz\Quiz;
use App\Models\Quiz\QuizAnswer;
use App\Models\Quiz\QuizQuestion;
use App\Models\Quiz\QuizTypeUserAccess;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        try {
            $quiz1 = Quiz::create([
                'name' => 'Tes Kepribadian Polri Bagian 1',
                'type_quiz_id' => 1,
                'is_random_question' => 0,
                'description' => 'Sebelum mengerjakan tes, bacalah petunjuk pengerjaan tes ini dengan seksama. Tes ini terdiri dari 40 soal berupa pernyataan diri.',
                'open_quiz' => null,
                'close_quiz' => null,
                'time_duration' => 7200,
            ]);

            $quiz1TypeUser = [];
            for ($i = 2; $i < 7; $i += 2) {
                $quiz1TypeUser[] = [
                    'quiz_id' => $quiz1->id,
                    'type_user_id' => $i,
                ];
            }
            QuizTypeUserAccess::insert($quiz1TypeUser);

            $quiz_question = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 1,
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
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 2,
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
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 3,
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
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 4,
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
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 5,
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
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 6,
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
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 7,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Berbicara di depan orang banyak sama nyamannya dengan berbicara di depan kelas.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question7->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question7->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question7->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question7->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 8
            $quiz_question8 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 8,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya akan membantu korban bencana, apabila mendapat keuntungan materi dari bantuan tersebut.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question8->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question8->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question8->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question8->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 9
            $quiz_question9 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 9,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya lebih suka berkumpul dengan teman-teman yang memiliki sifat yang hampir sama dengan saya.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question9->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question9->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question9->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question9->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 10
            $quiz_question10 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 10,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya tidak tahan apabila harus lembur sampai tengah malam.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question10->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question10->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question10->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question10->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 11
            $quiz_question11 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 11,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saat dinyatakan lulus SMA saya langsung pulang ke rumah.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question11->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question11->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question11->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question11->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 12
            $quiz_question12 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 12,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Lebih baik diam dahulu, saat ada yang menentang pendapat saya.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question12->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question12->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question12->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question12->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 13
            $quiz_question13 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 13,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya tidak suka jika waktu istirahat masih digunakan untuk menyelesaikan tugas-tugas kelompok.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question13->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question13->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question13->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question13->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 14
            $quiz_question14 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 14,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Lebih baik menunggu disuruh daripada menawarkan diri.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question14->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question14->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question14->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question14->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 15
            $quiz_question15 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 15,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Meskipun teman-teman saya sering kelewatan saat bercanda, namun saya tidak pernah merasa tersinggung sedikitpun.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question15->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question15->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question15->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question15->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 16
            $quiz_question16 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 16,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya akan memperjuangkan pendapat saya dengan keras walaupun orang tidak menyukainya.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question16->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question16->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question16->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question16->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 17
            $quiz_question17 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 17,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya bersedia mencukupi kekurangan apabila ada kebutuhan kelompok asal sesuai dengan kemampuan saya.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question17->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question17->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question17->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question17->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 18
            $quiz_question18 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 18,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Kegagalan dan keberhasilan ditentukan oleh seberapa keras usaha masing-masing orang.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question18->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question18->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question18->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question18->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 19
            $quiz_question19 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 19,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya akan memberikan sumbangan ketika ada orang lain yang memperhatikan walaupun jumlah sumbangan saya sedikit.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question19->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question19->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question19->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question19->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 20
            $quiz_question20 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 20,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya lebih suka mengikuti kegiatan dimana terdapat banyak teman yang saya kenal.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question20->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question20->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question20->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question20->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 21
            $quiz_question21 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 21,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya bersedia lembur sampai pagi demi selesainya tugas yang diberikan oleh guru.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question21->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question21->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question21->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question21->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 22
            $quiz_question22 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 22,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Mudah bagi saya untuk tidak terlarut dalam suasana.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question22->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question22->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question22->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question22->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 23
            $quiz_question23 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 23,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya memilih solusi yang paling sedikit berdampak negatif pada orang lain.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question23->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question23->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question23->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question23->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 24
            $quiz_question24 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 24,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya keberatan ketika banyak hal yang tidak sesuai antara keinginan pribadi dengan keputusan kelompok.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question24->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question24->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question24->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question24->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 25
            $quiz_question25 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 25,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Jangan pernah mencoba-coba untuk suatu hal yang sulit.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question25->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question25->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question25->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question25->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 26
            $quiz_question26 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 26,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Menurut saya berbuat baik tidak usah terlalu lama dipertimbangkan.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question26->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question26->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question26->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question26->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 27
            $quiz_question27 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 27,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Mudah bagi saya untuk beradaptasi di lingkungan yang baru.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question27->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question27->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question27->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question27->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 28
            $quiz_question28 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 28,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Banyaknya hambatan dalam penyelesaian tugas akan menguras energi saya.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question28->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question28->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question28->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question28->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 29
            $quiz_question29 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 29,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya terkadang mengalami kesulitan saat berkenalan dengan orang-orang yang memiliki karakter yang berbeda dengan saya.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question29->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question29->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question29->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question29->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 30
            $quiz_question30 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 30,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Adanya batas waktu dalam penyelesaian tugas membuat saya terbebani.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question30->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question30->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question30->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question30->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 31
            $quiz_question31 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 31,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Ketika ada korban kecelakaan saya cenderung berdiam diri karena itu tanggung jawab petugas.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question31->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question31->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question31->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question31->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 32
            $quiz_question32 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 32,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya akan tetap menjalankan keputusan kelompok meskipun tidak sesuai dengan prinsip diri saya.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question32->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question32->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question32->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question32->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 33
            $quiz_question33 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 33,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya termasuk orang yang biasa-biasa saja dibandingkan rekan yang lain.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question33->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question33->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question33->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question33->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 34
            $quiz_question34 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 34,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Apabila dokter langganan datang terlambat maka saya akan segera meninggalkan tempat prakteknya.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question34->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question34->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question34->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question34->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 35
            $quiz_question35 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 35,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Meskipun saya tidak setuju dengan aturan yang ada, saya tetap berusaha untuk mematuhinya.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question35->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question35->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question35->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question35->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 36
            $quiz_question36 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 36,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya memilih diam di tengah-tengah orang yang baru dikenal.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question36->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question36->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question36->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question36->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 37
            $quiz_question37 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 37,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya lebih senang melakukan tugas yang tidak memerlukan waktu lama dalam penyelesaiannnya.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question37->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question37->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question37->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question37->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 38
            $quiz_question38 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 38,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya iri jika sesuatu hal yang dilakukan itu meringankan beban orang lain.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question38->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question38->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question38->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question38->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 39
            $quiz_question39 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 39,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Selama tidak bersifat prinsip, saya mau dipersalahkan akibat kesalahan organisasi.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question39->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question39->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question39->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question39->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 40
            $quiz_question40 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 40,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya merasa kurang dapat memberikan dukungan dalam kegiatan kelompok yang pernah saya ikuti.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question40->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question40->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question40->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question40->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback jika ada error
            throw $e;
        }
    }
}
