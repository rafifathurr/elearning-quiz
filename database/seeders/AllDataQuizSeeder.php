<?php

namespace Database\Seeders;

use App\Models\Quiz\Quiz;
use App\Models\Quiz\QuizAnswer;
use App\Models\Quiz\QuizQuestion;
use App\Models\Quiz\QuizTypeUserAccess;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AllDataQuizSeeder extends Seeder
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
                'description' => 'Sebelum mengerjakan tes, bacalah petunjuk pengerjaan tes ini dengan seksama. Tes ini terdiri dari 105 soal berupa pernyataan diri.',
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
            // Soal nomor 41
            $quiz_question41 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 41,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saat dinyatakan lulus SMA saya merayakan dengan konvoi dengan teman-teman.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question41->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question41->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question41->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question41->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 42
            $quiz_question42 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 42,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya segera membalas perkataan teman yang menyakiti perasaan saya.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question42->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question42->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question42->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question42->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 43
            $quiz_question43 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 43,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Banyaknya tugas membuat saya merasa terbebani.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question43->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question43->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question43->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question43->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 44
            $quiz_question44 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 44,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya akan mengabaikan pekerjaan yang tidak saya sukai.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question44->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question44->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question44->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question44->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 45
            $quiz_question45 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 45,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya tidak suka terhadap teman yang tidak menuruti perkataan saya.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question45->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question45->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question45->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question45->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);
            // Soal nomor 46
            $quiz_question46 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 46,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya akan tetap bersama dengan kelompok walaupun tugas saya sudah selesai.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question46->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question46->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question46->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question46->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 47
            $quiz_question47 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 47,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya merasa salah tingkah jika bertemu orang yang baru saya kenal.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question47->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question47->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question47->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question47->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 48
            $quiz_question48 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 48,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya sedih melihat pengemis dipinggir jalan serta merasakan apa yang mereka rasakan.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question48->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question48->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question48->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question48->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 49
            $quiz_question49 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 49,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya suka dengan tempat kerja yang baru.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question49->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question49->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question49->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question49->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 50
            $quiz_question50 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 50,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya akan lebih memfokuskan diri saat menghadapi pekerjaan yang memerlukan tenaga dan konsentrasi tinggi.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question50->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question50->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question50->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question50->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);
            // Soal nomor 51
            $quiz_question51 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 51,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya akan menghindari orang-orang yang tidak saya sukai.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question51->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question51->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question51->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question51->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 52
            $quiz_question52 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 52,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Sedari kecil, saya terbiasa menuntut orangtua untuk selalu memenuhi setiap keinginan.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question52->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question52->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question52->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question52->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 53
            $quiz_question53 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 53,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya menjunjung tinggi peraturan yang ada di kelompok, walaupun tidak sesuai dengan kepentingan saya.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question53->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question53->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question53->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question53->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 54
            $quiz_question54 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 54,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Penampilan saya membuat orang merasa terkesan.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question54->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question54->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question54->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question54->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 55
            $quiz_question55 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 55,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya tidak pernah memberikan uang kepada pengemis yang badannya masih sehat karena saya anggap dia itu pemalas.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question55->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question55->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question55->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question55->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);
            // Soal nomor 56
            $quiz_question56 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 56,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya tidak suka membeda-bedakan dalam bergaul.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question56->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question56->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question56->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question56->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 57
            $quiz_question57 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 57,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya tidak perlu membantu apabila saya melihat suatu kecelakaan karena sudah banyak orang yang membantu apalagi saya terburu buru.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question57->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question57->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question57->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question57->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 58
            $quiz_question58 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 58,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya kesulitan memulai topik pembicaraan dengan orang baru kenal.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question58->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question58->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question58->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question58->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 59
            $quiz_question59 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 59,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Dalam menyelesaikan pekerjaan saya mengikuti suasana hati.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question59->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question59->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question59->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question59->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 60
            $quiz_question60 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 60,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya akan menghindari pekerjaan yang tidak saya sukai.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question60->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question60->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question60->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question60->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);
            // Soal nomor 61
            $quiz_question61 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 61,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya akan menerima kenyataan dengan lapang dada bila seandainya cita-cita yang saya mimpikan tidak dapat terwujud.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question61->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question61->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question61->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question61->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 62
            $quiz_question62 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 62,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya akan menjaga kewibawaan kelompok walaupun hal tersebut menurunkan kewibawaan saya.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question62->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question62->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question62->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question62->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 63
            $quiz_question63 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 63,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya merasa ragu jika harus memulai hal baru.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question63->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question63->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question63->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question63->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 64
            $quiz_question64 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 64,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Walau tidak dibayar saya akan ikut andil dalam tim yang bekerja untuk memadamkan api di lahan gambut yang terbakar.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question64->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question64->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question64->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question64->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 65
            $quiz_question65 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 65,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Dalam berhubungan dengan orang lain akan lebih senang jika mempunyai kegemaran yang sama.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question65->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question65->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question65->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question65->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);
            // Soal nomor 66
            $quiz_question66 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 66,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya mampu bekerja dalam waktu yang lama meskipun dalam kondisi lelah.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question66->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question66->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question66->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question66->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 67
            $quiz_question67 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 67,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya mampu mengalihkan rasa marah dengan kegiatan yang saya sukai.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question67->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question67->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question67->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question67->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 68
            $quiz_question68 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 68,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya akan melakukan apapun jika sudah memiliki keinginan.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question68->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question68->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question68->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question68->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 69
            $quiz_question69 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 69,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya mendukung keputusan kelompok walaupun tidak menguntungkan saya.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question69->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question69->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question69->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question69->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 70
            $quiz_question70 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 70,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya merasa memiliki kemampuan yang lebih diantara teman sekelas meskipun saya bukan juara kelas.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question70->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question70->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question70->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question70->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 71
            $quiz_question71 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 71,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Jika boleh memilih, saya akan menghindari berurusan dengan orang yang pernah mengkritik saya.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question71->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question71->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question71->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question71->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 72
            $quiz_question72 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 72,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Orang tahu kalau saya sedang marah.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question72->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question72->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question72->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question72->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 73
            $quiz_question73 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 73,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Kepentingan kelompok/organisasi di atas segalanya.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question73->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question73->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question73->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question73->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 74
            $quiz_question74 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 74,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya tetap dapat berbicara dengan lancar meskipun berhadapan dengan orang yang lebih pintar.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question74->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question74->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question74->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question74->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 75
            $quiz_question75 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 75,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya terbiasa mengumpulkan sumbangan dari donatur untuk dibagikan kepada orang lain yang berhak.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question75->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question75->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question75->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question75->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);
            // Soal nomor 76
            $quiz_question76 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 76,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya dapat berteman dengan siapa saja.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question76->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question76->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question76->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question76->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 77
            $quiz_question77 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 77,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Beban berat pekerjaan yang saya hadapi sering mengganggu kenyamanan tidur saya.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question77->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question77->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question77->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question77->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 78
            $quiz_question78 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 78,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Emosi saya tidak mudah terpancing.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question78->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question78->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question78->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question78->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 79
            $quiz_question79 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 79,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya lebih suka menyendiri ketika sedang marah.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question79->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question79->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question79->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question79->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 80
            $quiz_question80 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 80,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Rugi rasanya jika saya harus mengorbankan segalanya untuk kelompok, karena kelompok belum tentu membantu saya ketika mengalami masalah.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question80->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question80->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question80->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question80->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 81
            $quiz_question81 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 81,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya sering merasa tidak mampu bila diminta menyelesaikan tugas diluar batas kemampuan.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question81->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question81->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question81->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question81->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 82
            $quiz_question82 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 82,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Meskipun saya adalah korban gempa, saya tetap memberikan bantuan kepada orang lain.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question82->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question82->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question82->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question82->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 83
            $quiz_question83 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 83,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Dalam berteman di sekolah saya lebih mengedepankan keuntungan yang akan didapat.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question83->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question83->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question83->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question83->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 84
            $quiz_question84 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 84,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Hari-hari saya berlalu lebih lambat jika menghadapi kerja berat.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question84->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question84->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question84->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question84->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 85
            $quiz_question85 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 85,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Jika saya sedang marah nafsu makan saya berkurang.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question85->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question85->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question85->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question85->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 86
            $quiz_question86 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 86,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya tidak akan membela kelompok saya walaupun ada yang menyerang.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question86->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question86->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question86->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question86->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 87
            $quiz_question87 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 87,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Jarang sekali saya minta tolong kepada orang lain atau teman ketika mendapatkan tugas dari sekolah sesulit apapun.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question87->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question87->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question87->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question87->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 88
            $quiz_question88 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 88,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya kira wajar kalau seseorang mengumpat untuk mengungkapkan perasaan tidak senang kepada orang lain.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question88->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question88->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question88->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question88->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 89
            $quiz_question89 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 89,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Tanpa diminta saya akan memberi apa pun yang dibutuhkan tanpa melihat siapa orangnya.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question89->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question89->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question89->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question89->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 90
            $quiz_question90 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 90,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Dalam berhubungan dengan orang lain saya lebih mengutamakan bergaul dengan orang yang pandai.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question90->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question90->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question90->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question90->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 91
            $quiz_question91 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 91,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya tidak merasa terbebani disaat menyelesaikan pekerjaan ditambah tugas yang baru.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question91->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question91->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question91->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question91->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 92
            $quiz_question92 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 92,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Orang lain tidak tahu ketika saya sedang marah.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question92->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question92->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question92->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question92->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 93
            $quiz_question93 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 93,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Meskipun ada beberapa keputusan kelompok yang dinilai kurang tepat, saya tetap membela demi nama baik kelompok.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question93->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question93->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question93->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question93->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 94
            $quiz_question94 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 94,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya terbiasa berbicara dengan suara yang lantang.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question94->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question94->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question94->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question94->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 95
            $quiz_question95 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 95,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya memilih diam ketika ada permasalahan dengan orang lain.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question95->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question95->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question95->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question95->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 96
            $quiz_question96 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 96,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Banyak teman yang membutuhkan pertolongan saya sehingga saya menjadi orang yang penting di lingkungan sekitar saya.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question96->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question96->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question96->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question96->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 97
            $quiz_question97 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 97,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya mengenal akrab hampir semua petugas keamanan dan kebersihan sekolah.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question97->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question97->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question97->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question97->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 98
            $quiz_question98 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 98,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya masih tetap menyelesaikan pekerjaan yang ditugaskan sampai selesai meskipun menyita waktu istirahat.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question98->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question98->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question98->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question98->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 99
            $quiz_question99 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 99,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Tidak masalah bagi saya bila saya mengikuti kegiatan dimana tidak ada satupun orang yang saya kenal.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question99->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question99->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question99->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question99->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 100
            $quiz_question100 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 100,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya merasa terbebani melakukan kegiatan yang melebihi daya tahan fisik saya.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question100->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question100->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question100->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question100->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 101
            $quiz_question101 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 101,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya akan meninggalkan teman bicara ketika ucapannya menyinggung perasaan.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question101->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question101->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question101->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question101->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 102
            $quiz_question102 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 102,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya bisa menyembunyikan perasaan jengkel ketika ada orang yang mencoba mengusik saya.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question102->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question102->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question102->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question102->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 103
            $quiz_question103 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 103,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya merasa senang apabila dapat membantu orang lain, meskipun orang tersebut bersikap buruk terhadap saya.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question103->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question103->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question103->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question103->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 104
            $quiz_question104 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 104,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya tidak ragu menyeberang ke pihak lain, kalau kelompok sudah tidak membutuhkan saya.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question104->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question104->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question104->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question104->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);

            // Soal nomor 105
            $quiz_question105 = QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 105,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya sering merasa canggung apabila diminta berbicara di depan umum.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question105->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sangat Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question105->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tidak Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question105->id, 'is_answer' => 0, 'point' => 20, 'answer' => 'Setuju', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question105->id, 'is_answer' => 0, 'point' => 10, 'answer' => 'Sangat Setuju', 'attachment' => null]);


            // Quiz 2
            $quiz2 = Quiz::create([
                'name' => 'Tes Kepribadian Polri Bagian 2',
                'type_quiz_id' => 1,
                'is_random_question' => 0,
                'description' => 'Anda diminta menjawab 100 soal berupa pasangan pernyataan yang berkaitan dengan gambaran kehidupan sehari-hari. Tugas Anda adalah memilih salah satu pernyataan yang menurut Anda paling sesuai dengan diri yaitu pernyataan a atau b.',
                'open_quiz' => null,
                'close_quiz' => null,
                'time_duration' => 7200,
            ]);


            $quiz2TypeUser = [];
            for ($i = 2; $i < 7; $i += 2) {
                $quiz2TypeUser[] = [
                    'quiz_id' => $quiz2->id,
                    'type_user_id' => $i,
                ];
            }
            QuizTypeUserAccess::insert($quiz2TypeUser);

            $quiz_question_type_2_no1 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 1,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Dalam setiap diskusi saya akan:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no1->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Dalam setiap diskusi, saya aktif berpendapat walaupun belum tentu diterima.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no1->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Dalam setiap diskusi saya selalu berusaha meyakinkan kebenaran ide-ide saya', 'attachment' => null]);


            $quiz_question_type_2_no2 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 2,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Dalam menyampaikan informasi penting biasanya saya akan:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no2->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Mengatakan apa adanya meskipun membuat perasaan kurang nyaman', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no2->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Mengatakan dengan menyesuaikan dan mempertimbangkan siapa pendengarnya.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 3
            $quiz_question_type_2_no3 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 3,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Apabila ada orang membuat lelucon yang mirip dengan pengalaman saya, maka:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no3->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya akan ikut tertawa karena pengalaman tersebut benar-benar berkesan.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no3->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya merasa kurang pantas jika pengalaman saya dijadikan sebagai lelucon.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 4
            $quiz_question_type_2_no4 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 4,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Apabila ada teman yang menceritakan tentang dirinya, saya akan:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no4->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Mendengarkan seksama sebagai bentuk perhatian saya kepadanya.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no4->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Mendengarkan seksama agar mengetahui keadaan diri teman saya tersebut.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 5
            $quiz_question_type_2_no5 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 5,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Untuk menyelesaikan pekerjaan yang berat, maka:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no5->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya mengatakan akan membuatkan jadwal bergilir.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no5->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya mengatakan akan memberikan insentif bagi anggota.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 6
            $quiz_question_type_2_no6 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 6,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no6->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Dengarkan saran orang lain, tetapi tetaplah pada konsep sendiri yang sudah pasti.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no6->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Pertimbangkan saran-saran orang lain meski berbeda dengan konsep saya.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 7
            $quiz_question_type_2_no7 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 7,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no7->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Apapun yang terjadi, saya tidak akan menceritakan rahasia teman.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no7->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Dalam keadaan terpaksa saya seminimal mungkin membuka rahasia teman.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 8
            $quiz_question_type_2_no8 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 8,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saya mudah mengatakan tidak untuk hal-hal berlawanan dengan nurani saya.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no8->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Tidak sesuai dengan diri saya.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no8->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Sangat sesuai dengan diri saya.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 9
            $quiz_question_type_2_no9 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 9,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no9->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Untuk menjalani kehidupan sehari-hari saya menjalani apa adanya.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no9->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Untuk menjalani kehidupan sehari-hari saya menyusun jadwal kegiatan.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 10
            $quiz_question_type_2_no10 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 10,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Jika belum ada kepastian tentang pemberlakuan kebijakan baru, sikap saya:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no10->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Menyiapkan diri meski akhirnya tidak jadi diberlakukan.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no10->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Menghadap pimpinan untuk menanyakan kepastian akan pemberlakuannya.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 11
            $quiz_question_type_2_no11 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 11,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no11->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Kadang saya bisa memberikan masukan jika dianggap perlu.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no11->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya akan mengikuti keputusan atasan dan tidak memberikan masukan.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 12
            $quiz_question_type_2_no12 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 12,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no12->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Bagi saya cita-cita tidak harus diperjuangkan karena belum tentu tercapai.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no12->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Jika saya mencita-citakan sesuatu, saya akan berusaha keras untuk mencapainya.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 13
            $quiz_question_type_2_no13 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 13,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no13->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Agar saya ahli maka semua bidang akan saya tekuni.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no13->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Agar menjadi ahli saya ingin memfokuskan pada satu bidang saja.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 14
            $quiz_question_type_2_no14 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 14,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no14->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Pekerjaan mendesak dapat diselesaikan dengan mencari alternatif-alternatif agar anggota dapat bekerja dengan senang.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no14->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Dalam menyelesaikan pekerjaan yang mendesak saya arahkan anggota dengan cara yang saya anggap paling tepat.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 15
            $quiz_question_type_2_no15 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 15,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Dalam situasi diskusi yang mulai memanas, saya sampaikan informasi apa adanya tanpa ditutup-tutupi.',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no15->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya sampaikan informasi apa adanya tanpa ditutup-tutupi.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no15->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya akan memilih informasi yang disampaikan agar tidak memperkeruh situasi.', 'attachment' => null]);


            // Quiz 2 - Soal nomor 16
            $quiz_question_type_2_no16 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 16,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no16->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Keinginan selalu saya sesuaikan dengan kemampuan yang saya miliki.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no16->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Apabila kita ingin sukses, maka kita harus punya cita-cita setinggi-tingginya.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 17
            $quiz_question_type_2_no17 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 17,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no17->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Ketika memperhatikan seseorang, saya berusaha memahami perasaannya dengan menimbang-nimbang makna ekspresi wajah, gerak-gerik tubuh dan suaranya.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no17->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Ketika memperhatikan seseorang, saya dapat memahami perasaannya karena teringat atas perasaan saya saat menghadapi hal serupa.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 18
            $quiz_question_type_2_no18 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 18,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Setiap kali anak saya menghadapi ujian sekolah:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no18->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya memberi target nilai dan menjanjikan hadiah apabila ia dapat mencapainya.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no18->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Setiap hari saya mengingatkan dan memberi semangat agar anak saya belajar.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 19
            $quiz_question_type_2_no19 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 19,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Apabila saat mendiskusikan tanggal pelaksanaan kegiatan ada perbedaan kesepakatan waktu, maka:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no19->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Membatalkan rencana wisata atau menunda beberapa minggu lagi.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no19->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Menentukan hari yang sedikit menimbulkan kerugian kedua belah pihak.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 20
            $quiz_question_type_2_no20 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 20,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no20->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya siap dipersalahkan untuk perbuatan yang dilakukan staf.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no20->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Orang yang bersalah harus dicari untuk mempertanggungjawabkan kesalahannya.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 21
            $quiz_question_type_2_no21 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 21,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saat saya memiliki keinginan, maka:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no21->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Akan saya sampaikan keinginan saya secara langsung kepada atasan.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no21->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya mengharapkan atasan memahami dan mengerti keinginan saya.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 22
            $quiz_question_type_2_no22 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 22,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no22->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya ingat atas janji-janji yang saya buat, tanpa perlu menuliskannya.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no22->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya terbiasa menulis agenda untuk mengingatkan atas janji yang harus ditepati.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 23
            $quiz_question_type_2_no23 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 23,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saat menerima tugas tambahan yang tidak sesuai dengan bidang saya, maka:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no23->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Meminta penjelasan kepada pimpinan alasan penugasan tersebut.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no23->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tetap melaksanakannya meski saya masih memiliki tugas yang banyak.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 24
            $quiz_question_type_2_no24 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 24,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no24->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya tidak segan mengajak rekan-rekan melakukan sesuatu yang penting.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no24->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya hati-hati mengajak rekan-rekan untuk melakukan sesuatu yang penting.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 25
            $quiz_question_type_2_no25 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 25,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Jika saya diminta menyelesaikan tugas dalam waktu segera, maka:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no25->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya akan merasa puas apabila pekerjaan saya diakui oleh banyak orang.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no25->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya akan merasa puas apapun hasil yang sudah saya kerjakan.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 26 (Pertanyaan tidak ada, hanya opsi)
            $quiz_question_type_2_no26 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 26,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null, // Tidak ada pertanyaan
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no26->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya akan menepati janji, walaupun harus menempuh jarak yang agak jauh.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no26->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya akan membatalkan janji jika ada kendala di jalan.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 27 (Pertanyaan tidak ada, hanya opsi)
            $quiz_question_type_2_no27 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 27,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null, // Tidak ada pertanyaan
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no27->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Dalam rapat saya akan menyesuaikan dengan pendapat mayoritas peserta rapat.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no27->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Dalam rapat saya akan mengungkapkan pendapat, walaupun bertentangan dengan pendapat sebagian besar peserta rapat.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 28 (Pertanyaan ada, dengan opsi)
            $quiz_question_type_2_no28 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 28,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Dalam mengerjakan tugas-tugas di kantor saya selalu:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no28->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Mengerjakan tugas yang ada sesuai dengan kondisi kesibukan saya.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no28->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Mempunyai target waktu untuk penyelesaian pekerjaan.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 29 (Pertanyaan ada, dengan opsi)
            $quiz_question_type_2_no29 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 29,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Dalam situasi emosi marah,',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no29->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya dapat mengatur ekspresi emosi saya.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no29->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya menenangkan diri agar tidak semakin marah.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 30 (Pertanyaan ada, dengan opsi)
            $quiz_question_type_2_no30 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 30,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Dalam waktu terbatas yang dapat saya lakukan adalah:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no30->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Berusaha menyediakan fasilitas yang dapat digunakan warga bersama-sama.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no30->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Berusaha menyediakan perlengkapan olah raga dan mengajak bermain bersama.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 31 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no31 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 31,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Mempertimbangkan kemampuan mempertahankan semangat kerja:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no31->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya akan berhasil menyelesaikan tugas dalam seminggu.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no31->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya akan berhasil menyelesaikan tugas dalam waktu sebulan.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 32 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no32 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 32,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no32->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Apapun resikonya, setiap janji harus ditepati.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no32->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Janji tidak harus selalu dipenuhi apabila situasi dan kondisinya berubah.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 33 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no33 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 33,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no33->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya perlu melakukan penyesuaian bila bekerja sama dengan orang yang baru.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no33->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya tidak pernah kebingungan meski harus bekerja sama dengan banyak orang.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 34 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no34 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 34,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no34->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya lebih mengacu pada dasar-dasar kebenaran daripada yang lain.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no34->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya lebih mengacu pada dasar-dasar keadilan.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 35 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no35 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 35,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Kesempatan mengikuti seleksi pasukan perdamaian PBB diadakan 3 bulan lagi. Sementara kemampuan bahasa inggris saya masih terbatas, tindakan saya:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no35->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Tidak mengikuti seleksi tersebut karena waktu yang tersedia terlalu singkat.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no35->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Mengikuti seleksi tersebut karena masih ada waktu untuk belajar.', 'attachment' => null]);


            // Quiz 2 - Soal nomor 36 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no36 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 36,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Pekerjaan harus segera diselesaikan dalam batas waktu tertentu, namun anggota yang ditugaskan selalu salah maka sikap saya:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no36->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Memanggil anggota yang ditugaskan untuk mengetahui kendalanya.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no36->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Segera memberikan pekerjaan kepada anggota yang mampu menyelesaikan.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 37 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no37 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 37,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Menghadapi orang yang mudah putus asa, saya akan:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no37->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Terus menerus mendorongnya untuk mengerjakan tugasnya.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no37->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Memberikan pujian ketika dia menunjukkan usaha yang positif.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 38 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no38 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 38,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no38->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saran dan kritik dari warga masyarakat atas kebijakan saya yang disampaikan lewat surat atau media akan saya jadikan referensi.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no38->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saran dan kritik dari warga masyarakat atas kebijakan saya yang disampaikan lewat surat atau media merupakan masukan untuk merevisi kebijakan saya.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 39 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no39 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 39,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no39->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Kesalahan dalam dinas yang dilakukan anggota adalah tanggung jawab saya sebagai pimpinannya untuk menyelesaikan.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no39->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya yakin kesalahan yang dilakukan anggota dalam dinas adalah akibat ketidaksengajaan saja.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 40 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no40 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 40,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no40->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya menyampaikan pendapat dan kritikan pada orang lain secara spontan.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no40->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya bisa berkata “ya” untuk sesuatu yang seharusnya saya berkata “tidak” pada situasi genting.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 41 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no41 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 41,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no41->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya tidak mudah berjanji, karena menimbulkan perasaan tidak enak jika tidak ditepati.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no41->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Memberikan janji pada seseorang menunjukkan perhatian kita, dan kalaupun tidak dapat dipenuhi tidak terlalu masalah sejauh ada alasan yang tepat.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 42 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no42 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 42,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Menghadapi tugas yang beruntun dari pimpinan, maka:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no42->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Menyampaikan kepada pimpinan agar tugas tersebut dilakukan rekan lain.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no42->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Meski merasa dongkol dan melelahkan akan berusaha melaksanakannya.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 43 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no43 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 43,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Peran yang saya pilih:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no43->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Membelikan perlengkapan olah raga untuk klub remaja di lingkungan RW.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no43->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Menjadi pengasuh tim sepak bola remaja di lingkungan RW.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 44 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no44 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 44,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Untuk mencapai keinginan, maka saya:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no44->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Menyelaraskan dengan kepentingan teman-teman, sehingga kadang-kadang keinginan tersebut menjadi tidak tercapai.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no44->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Berusaha sekuat tenaga walaupun harus banyak berkorban.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 45 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no45 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 45,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no45->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya akan menggunakan semua cara untuk menyelesaikan masalah.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no45->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya memilih menggunakan satu cara yang paling sering saya gunakan.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 46 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no46 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 46,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no46->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Menghadapi pekerjaan yang sulit, saya meminta tim untuk fokus pada pekerjaan.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no46->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Dalam menghadapi tugas yang sulit, saya mencari cara bagaimana agar teman-teman bersedia melaksanakannya.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 47 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no47 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 47,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no47->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya terbiasa berbicara apa adanya sesuai fakta-fakta.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no47->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Untuk menjaga perasaan orang lain kadang saya harus bicara tidak sesuai fakta.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 48 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no48 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 48,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no48->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya tidak malu menceritakan tingkah laku bodoh yang pernah saya lakukan sebagai lelucon.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no48->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Sesekali saya bercerita tentang tingkah laku konyol teman-teman saya sebagai lelucon.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 49 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no49 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 49,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Jika ada staf saya yang tidak masuk kantor dengan alasan tidak jelas, maka:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no49->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya akan bertanya kepadanya mengapa ia tidak masuk kantor.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no49->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya akan memberi teguran dan sanksi karena tidak masuk kantor tanpa izin.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 50 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no50 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 50,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Agar anggota tim saya dapat menunjukkan semangat yang tinggi maka:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no50->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya menyatakan pujian pada mereka secara kelompok.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no50->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya menyatakan pujian pada mereka secara perorangan.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 51 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no51 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 51,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Ketika rencana yang saya buat sudah selesai, maka saya akan:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no51->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Meminta masukan pada rekan barangkali ada yang perlu disempurnakan.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no51->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Menyampaikan rencana tersebut karena saya yakin sudah baik.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 52 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no52 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 52,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no52->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya akan menjaga harga diri rekan saya, sejauh sikap dan perilakunya membawa manfaat bagi banyak orang.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no52->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya akan menjaga harga diri rekan saya, meskipun karena itu saya menjadi tercela.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 53 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no53 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 53,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no53->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya menyarankan agar metode kerja saya digunakan oleh seluruh tim.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no53->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya akan selalu berupaya agar metode kerja saya digunakan oleh seluruh tim.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 54 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no54 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 54,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no54->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Dalam pengalaman hidup saya, orang-orang mengharapkan saya mengatakan kebenaran apa adanya.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no54->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Dalam pengalaman hidup saya, berkata apa adanya hanya memperburuk hubungan.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 55 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no55 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 55,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Terhadap kelebihan dan kekurangan diri dalam melaksanakan tugas, saya:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no55->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Menghindari pekerjaan yang tidak mampu saya kerjakan.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no55->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Memaksakan diri menghadapi tugas agar kemampuan meningkat.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 56 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no56 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 56,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Ketika saya ditugaskan untuk mengarahkan rekan-rekan, mengerjakan suatu pekerjaan yang membosankan, maka:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no56->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya akan mendorong rekan-rekan mengerjakannya dengan menginformasikan bahwa tugas yang akan dihadapi menuntut keseriusan karena sifat pekerjaan ini membosankan.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no56->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya akan mencoba mengingat suasana hati saya ketika menghadapi tugas sejenis, dan hal-hal yang dapat merangsang semangat saya, untuk saya terapkan pada rekan-rekan.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 57 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no57 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 57,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Untuk memaksimalkan kinerja tim, sebagai ketua tim saya akan:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no57->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Mengawasi pelaksanaan tugas yang dilakukan anggota tim agar bekerja tepat waktu.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no57->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Memberikan bonus kepada anggota tim yang berhasil bekerja tepat waktu.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 58 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no58 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 58,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no58->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Apabila mengalami kesulitan pribadi, saya minta pendapat kepada teman dekat.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no58->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Masalah pribadi saya, akan saya atasi sendiri tanpa melibatkan orang lain.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 59 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no59 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 59,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no59->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Relasi antara orang satu dan lainnya terikat oleh kasih sayang, maka pengorbanan yang diberikan bersifat total.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no59->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Relasi antara orang satu dan lainnya terikat oleh kepentingan, maka pengorbanan yang kita berikan tentu sebanding dengan manfaat yang diterima.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 60 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no60 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 60,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Dalam menyampaikan kritik dan komentar atas perilaku teman:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no60->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya akan mencari saat yang tepat untuk menyampaikannya.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no60->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya akan menyampaikan secara langsung pada saat itu juga.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 61 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no61 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 61,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no61->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya mengandalkan ingatan atas rencana-rencana kegiatan yang saya lakukan.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no61->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya mencatat kegiatan-kegiatan yang akan saya lakukan.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 62 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no62 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 62,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saat pimpinan menerapkan kebijakan pengurangan tunjangan kinerja maka:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no62->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya akan menghemat beberapa pengeluaran rutin.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no62->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Setiap orang mempunyai kepentingan sehingga harus dipertimbangkan alasanya.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 63 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no63 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 63,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Dalam menghadapi suatu pekerjaan besar, maka sikap saya :',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no63->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Berinisiatif memberikan alternatif solusi dan menggerakkan semua yang terlibat.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no63->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Mengambil peran proporsional sesuai fungsi dan jabatan saya.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 64 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no64 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 64,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no64->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya berusaha untuk mencapai prestasi dalam setiap pekerjaan.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no64->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya akan meningkatkan prestasi kerja pada bidang yang saya sukai.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 65 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no65 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 65,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Sebagai ketua kelompok, apa yang telah saya ucapkan dalam suatu rapat:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no65->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Hendaknya dipandang suatu bijak untuk memperoleh simpati anggota tim.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no65->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya tindak lanjuti dengan memerintahkan anggota tim untuk melaksanakannya.', 'attachment' => null]);


            // Quiz 2 - Soal nomor 66 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no66 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 66,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no66->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Dalam pekerjaan yang mendesak, saya arahkan anggota dengan cara yang saya anggap paling tepat.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no66->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Dalam pekerjaan yang mendesak, saya berusaha menyelesaikan dengan cara sendiri.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 67 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no67 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 67,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no67->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya akan mendiamkan saja ketika pimpinan melakukan kesalahan.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no67->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya akan mengatakan salah kepada pimpinan jika apa yang dilakukannya salah.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 68 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no68 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 68,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no68->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya sangat senang mengunjungi pameran mobil mewah, namun saya menyadari bahwa saya tidak bisa memiliki mobil tersebut.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no68->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya sangat senang mengunjungi pameran mobil mewah dan saya yakin bisa memiliki mobil tersebut dengan cara kredit.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 69 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no69 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 69,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no69->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Meskipun sudah cukup dekat dengan teman-teman, tetapi saya merasa belum cukup mengenal mereka, oleh karena itu saya selalu menaruh perhatian atas kebiasaan-kebiasaan uniknya.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no69->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya memahami pribadi semua teman dekat saya.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 70 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no70 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 70,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Untuk mendorong anggota menjadi kreatif dalam bekerja maka saya akan:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no70->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Menyiapkan berbagai fasilitas yang berhubungan dengan pekerjaannya.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no70->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Menjanjikan bonus atas kreatifitas yang mereka ciptakan.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 71 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no71 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 71,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no71->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya sangat menghargai saran yang diberikan kepada saya.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no71->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya percaya bahwa saya mampu mengambil keputusan sendiri.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 72 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no72 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 72,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no72->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sebagai ketua RT saya akan membela kepentingan seluruh warga secara seimbang.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no72->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Sebagai ketua RT saya akan memberi perhatian lebih besar terhadap warga yang berpartisipasi lebih banyak dalam pembangunan lingkungan.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 73 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no73 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 73,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Dalam suatu rapat jika yang dikemukakan pimpinan rapat adalah salah, maka saya:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no73->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Akan memberikan koreksi pada saat itu juga.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no73->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Diam saja karena kurang etis jika mengoreksi pada saat itu.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 74 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no74 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 74,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no74->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Janganlah terlalu kaku terhadap waktu ketika bekerja.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no74->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tugas-tugas harus selesai tepat waktu.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 75 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no75 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 75,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Saat sedang mengantri di tempat praktek dokter, sementara dokternya belum datang: ',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no75->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya lebih baik meninggalkan tempat praktek dan mencari dokter lain.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no75->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya akan berbincang – bincang dengan sesame pasien.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 76 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no76 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 76,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Pada setiap kegiatan di lingkungan tempat tinggal:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no76->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya bersedia ditunjuk menjadi panitia.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no76->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya akan mengambil peran secara aktif.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 77 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no77 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 77,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no77->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sekali tujuan sudah saya tentukan, saya fokus pada upaya mencapainya.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no77->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Suatu tujuan yang sudah saya tentukan, tidak menyebabkan saya kehilangan fokus pada tujuan baru yang saya buat.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 78 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no78 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 78,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no78->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Sebagai pimpinan haruslah membuat kebijakan baru sesuai dengan perkembangan lingkungan eksternal.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no78->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Kebijakan-kebijakan pimpinan yang terdahulu harus dilanjutkan dan dijabarkan supaya dapat dilaksanakan.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 79 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no79 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 79,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no79->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya tidak merasa ragu untuk menyampaikan maksud kepada orang lain.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no79->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya kadang-kadang membatalkan menyampaikan maksud kepada orang lain karena perasaan kurang enak.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 80 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no80 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 80,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no80->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya setuju setiap orang harus membuat ketentuan untuk diri sendiri dan mengikuti sepenuhnya ketentuannya itu.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no80->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya berpandangan orang tidak perlu mengekang diri sendiri dengan membuat ketentuan-ketentuan yang harus dipatuhinya.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 81 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no81 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 81,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Ketika saya terlambat menyelesaikan tugas yang diberikan atasan:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no81->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya akan berusaha memperbaiki kebiasaan penyelesaian tugas.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no81->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya akan memberikan penjelasan yang masuk akal agar atasan tidak marah.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 82 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no82 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 82,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no82->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya membagi tugas sesuai kemampuan anggota tim.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no82->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya membagi tugas secara merata kepada seluruh anggota tim.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 83 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no83 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 83,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Ada kalanya orang merasa bahwa pekerjaan yang dihadapi tidak begitu penting, maka:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no83->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya akan bekerja seadanya, tanpa memikirkan hasil akhirnya.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no83->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya bekerja semestinya dan menikmati kepuasan atas hasil kerja saya itu.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 84 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no84 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 84,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no84->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Pernyataan pejabat publik adalah pernyataan politik, wajar kalau tidak direalisasikan.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no84->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Sebagai seorang pejabat publik, jangan sekali-kali membuat pernyataan yang tidak dapat direalisasikan.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 85 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no85 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 85,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no85->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Dalam membuat keputusan saya akan mengingat bahwa pimpinan mempunyai hak prerogatif untuk memberikan arah organisasi.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no85->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Sebelum mengambil keputusan saya akan melakukan pendekatan kepada anggota yang merasa keberatan.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 86 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no86 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 86,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no86->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya menghargai orang-orang yang jujur.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no86->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya menghargai orang-orang yang bijak.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 87 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no87 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 87,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Dalam setiap diskusi yang dihadiri oleh para pejabat dan pakar, maka :',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no87->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya akan mengikuti sampai selesai jalannya diskusi.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no87->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya akan menanyakan hal-hal yang belum saya pahami dan selanjutnya mengemukakan pendapat-pendapat saya.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 88 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no88 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 88,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no88->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Makin hari makin bertambah pemahaman saya atas pribadi teman-teman.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no88->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Makin sering bertemu, makin akrab hubungan saya dengan teman-teman.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 89 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no89 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 89,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Agar anggota tim menyelesaikan tugas sesuai jadwal, maka saya akan :',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no89->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Hadir dan ikut serta dalam penyelesaian tugas tersebut.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no89->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Menjanjikan liburan bersama jika tugas selesai.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 90 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no90 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 90,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no90->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Tampilkan dirimu yang terbaik dan tutupilah kelemahanmu, citra diri akan naik.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no90->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Tampilkan diri apa adanya, tidak ada ruginya orang lain mengetahui kekurangan maupun kelebihan kita.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 91 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no91 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 91,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no91->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya akan membantu orang yang saya kenal.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no91->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya akan membantu orang lain tanpa diminta.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 92 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no92 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 92,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no92->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Tidak semua ide dan gagasan dapat saya kemukakan secara bebas dalam rapat.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no92->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya merasa bebas mengemukakan ide dan gagasan dalam rapat.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 93 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no93 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 93,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no93->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya tidak membuat ketentuan sendiri karena hanya akan menghambat kreativitas.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no93->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya membuat ketentuan-ketentuan sendiri untuk dipedomani dalam bertindak.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 94 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no94 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 94,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Anak saya biasanya paling lama pulang kerumah pukul 9 malam. Ketika anak saya belum pulang hingga pukul 10 malam dan tidak ada pemberitahuan, maka :',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no94->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya merasa sedikit khawatir dan berharap tidak ada masalah yang dihadapinya.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no94->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya menjadi sangat cemas dan akan mencari keterangan dari teman-temannya.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 95 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no95 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 95,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no95->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Tugas-tugas tambahan selalu saya bagi habis sesuai fungsi dan peranannya.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no95->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya selalu memberikan tugas-tugas tambahan kepada anggota berdasarkan kemampuan perorangan.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 96 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no96 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 96,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => 'Untuk dapat menduduki jabatan yang lebih tinggi:',
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no96->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya harus bisa menunjukkan prestasi yang lebih baik dari teman-teman.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no96->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya harus melakukan berbagai pendekatan dengan atasan agar dapat menduduki jabatan yang lebih tinggi.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 97 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no97 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 97,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no97->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Kadang-kadang komitmen yang sudah kita buat dapat berubah sesuai situasi.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no97->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Ketika saya berkomitmen, berarti saya harus memenuhi komitmen tersebut.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 98 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no98 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 98,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no98->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Seandainya saya dipekerjakan sebagai salesman, saya bekerja keras untuk mempelajari teknik-teknik pemasaran.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no98->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Seandainya saya dipekerjakan sebagai salesman, saya dapat menyenanginya karena mudah bagi saya untuk meyakinkan orang.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 99 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no99 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 99,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no99->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya mudah menyesuaikan dengan situasi dan kondisi yang ada pada lingkungan.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no99->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya tidak mudah terpengaruh oleh lingkungan dan situasi yang berbeda.', 'attachment' => null]);

            // Quiz 2 - Soal nomor 100 (Pertanyaan dengan opsi)
            $quiz_question_type_2_no100 = QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'is_random_answer' => 0,
                'is_generate_random_answer' => 0,
                'order' => 100,
                'direction_question' => 'Pilih yang sesuai dengan diri anda',
                'question' => null,
                'description' => null,
                'time_duration' => 30,
            ]);

            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no100->id, 'is_answer' => 0, 'point' => 40, 'answer' => 'Saya melihat bahwa prestasi saya selama ini terkait langsung dengan kelebihan dan kelemahan saya.', 'attachment' => null]);
            QuizAnswer::create(['quiz_question_id' => $quiz_question_type_2_no100->id, 'is_answer' => 0, 'point' => 30, 'answer' => 'Saya yakin bahwa prestasi-prestasi yang saya capai tidak berhubungan dengan kelebihan dan kelemahan diri saya.', 'attachment' => null]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback jika ada error
            throw $e;
        }
    }
}
