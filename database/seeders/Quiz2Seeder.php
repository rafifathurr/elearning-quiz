<?php

namespace Database\Seeders;

use App\Models\Quiz\Quiz;
use App\Models\Quiz\QuizAnswer;
use App\Models\Quiz\QuizQuestion;
use App\Models\Quiz\QuizTypeUserAccess;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Quiz2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        try {
            // Quiz 2
            $quiz2 = Quiz::create([
                'name' => 'Tes Kepribadian Polri Bagian 2',
                'type_quiz_id' => 1,
                'is_random_question' => 0,
                'description' => 'Anda diminta menjawab 60 soal berupa pasangan pernyataan yang berkaitan dengan gambaran kehidupan sehari-hari. Tugas Anda adalah memilih salah satu pernyataan yang menurut Anda paling sesuai dengan diri yaitu pernyataan a atau b.',
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




            DB::commit();
        } catch (Exception $e) {
            DB::rollBack(); // Rollback jika ada error
            throw $e;
        }
    }
}
