<?php

namespace App\Http\Controllers;

use App\Models\AspectQuestion;
use App\Models\Quiz\QuizAnswer;
use App\Models\Quiz\QuizQuestion;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class QuestionController extends Controller
{

    public function index()
    {
        $datatable_route = route('master.question.dataTable');
        $aspects = AspectQuestion::whereNull('deleted_at')->get();
        return view('master.question.index', compact('datatable_route', 'aspects'));
    }


    public function dataTable()
    {
        $aspectFilter = request()->get('aspect');
        $typeAspectFilter = request()->get('type_aspect');
        $question = QuizQuestion::query()->whereNull('deleted_at');

        if ($aspectFilter) {
            $question->where(function ($query) use ($aspectFilter) {
                $query->where('aspect', 'LIKE', "%{$aspectFilter}%");
            });
        }

        if ($typeAspectFilter) {
            $question->where(function ($query) use ($typeAspectFilter) {
                $aspectIds = AspectQuestion::where('type_aspect', $typeAspectFilter)->pluck('id')->toArray();

                foreach ($aspectIds as $aspectId) {
                    $query->orWhere('aspect', 'LIKE', "%|{$aspectId}|%");
                }
            });
        }


        $dataTable = DataTables::of($question)
            ->addIndexColumn()
            ->addColumn('level', function ($data) {
                $list_view = '<div class="text-center">';

                // Jika level 0, tampilkan semua level (Level 1, Level 2, Level 3)
                if ($data->level == 0) {
                    $list_view .= '<span class="badge bg-primary p-2 m-2" style="font-size: 0.9rem; font-weight: bold;">' . 'Level 1' . '</span>';
                    $list_view .= '<span class="badge bg-primary p-2 m-2" style="font-size: 0.9rem; font-weight: bold;">' . 'Level 2' . '</span>';
                    $list_view .= '<span class="badge bg-primary p-2 m-2" style="font-size: 0.9rem; font-weight: bold;">' . 'Level 3' . '</span>';
                } else {
                    // Jika level bukan 0, tampilkan level yang sesuai dari data yang ada
                    $levels = explode('|', $data->level);
                    foreach ($levels as $level) {
                        // Pastikan level hanya menampilkan data yang valid
                        if (in_array($level, [1, 2, 3])) {
                            $list_view .= '<span class="badge bg-primary p-2 m-2" style="font-size: 0.9rem; font-weight: bold;">' . 'Level ' . $level . '</span>';
                        }
                    }
                }

                $list_view .= '</div>';
                return $list_view;
            })
            ->addColumn('question', function ($data) {
                $text = strip_tags($data->question);
                $words = explode(' ', $text);
                if (count($words) > 30) {
                    $text = implode(' ', array_slice($words, 0, 30)) . '...';
                }
                return $text;
            })

            ->filterColumn('question', function ($query, $keyword) {
                $query->whereRaw("LOWER(REPLACE(quiz_question.question, '<', '')) LIKE ?", ["%" . strtolower($keyword) . "%"]);
            })

            ->addColumn('aspect', function ($data) {
                $list_view = '<div class="text-justify">';
                $aspects = explode('|', $data->aspect);
                $found = false;

                foreach ($aspects as $aspect) {
                    $aspectQuestion = AspectQuestion::where('id', $aspect)->first();
                    if ($aspectQuestion) {
                        $list_view .= '<span class="badge bg-primary p-2 m-2" style="font-size: 0.9rem; font-weight: bold;">' . $aspectQuestion->name . '</span >';
                        $found = true;
                    }
                }

                if (!$found) {
                    $allAspects = AspectQuestion::all();
                    foreach ($allAspects as $aspect) {
                        $list_view .= '<span class="badge bg-primary p-2 m-2" style="font-size: 0.7rem; font-weight: bold;">' . $aspect->name . '</span>';
                    }
                }

                $list_view .= '</div>';
                return $list_view;
            })
            ->filterColumn('aspect', function ($query, $keyword) {
                // Cari nama aspek berdasarkan ID di kolom `aspect`
                $aspectIds = AspectQuestion::where('name', 'LIKE', '%' . $keyword . '%')->pluck('id')->toArray();

                // Filter query untuk mencocokkan ID aspek dengan kolom `aspect`
                $query->where(function ($subQuery) use ($aspectIds) {
                    foreach ($aspectIds as $aspectId) {
                        $subQuery->orWhere('aspect', 'LIKE', '%' . $aspectId . '%');
                    }
                });
            })

            ->addColumn('action', function ($data) {
                $btn_action = '<div align="center">';
                $btn_action .= '<a href="' . route('master.question.show', ['id' => $data->id]) . '" class="btn btn-sm btn-info my-1"><i class="fas fa-eye"></i></a>';
                $btn_action .= '<a href="' . route('master.question.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning my-1 ml-1"><i class="fas fa-pencil-alt"></i></a>';
                $btn_action .= '<button onclick="destroyRecord(' . $data->id . ')" class="btn btn-sm btn-danger my-1 ml-1"><i class="fas fa-trash"></i></button>';
                $btn_action .= '</div>';
                return $btn_action;
            })
            ->only(['question', 'aspect', 'description', 'level', 'action'])
            ->rawColumns(['action', 'question', 'description', 'level', 'aspect'])
            ->with('draw', request('draw'))
            ->make(true);

        return $dataTable;
    }

    public function create()
    {
        $data['aspects'] = AspectQuestion::whereNull('deleted_at')->get();
        return view('master.question.create', $data);
    }

    public function append(Request $request)
    {
        if (isset($request->question) || isset($request->answer)) {
            if ($request->question) {
                return $this->appendQuestion(null, $request->increment);
            } else {
                return $this->appendAnswer(null, $request->increment, $request->parent);
            }
        } else {
            return response()->json(['message' => 'Gagal'], 400);
        }
    }

    private function appendQuestion(QuizQuestion $quiz_question = null, $increment, $disabled = '')
    {
        $data['disabled'] = $disabled;
        $data['quiz_question'] = $quiz_question;
        $data['increment'] = $increment;
        $data['type_quiz'] = AspectQuestion::whereNull('deleted_at')->get();

        if (!is_null($quiz_question)) {
            foreach ($quiz_question->quizAnswer as $index => $quiz_answer) {
                if (is_null($quiz_answer->deleted_at)) {
                    $data['quiz_answer'][$index + 1] = $this->appendAnswer($quiz_answer, $index + 1, $increment, $disabled);
                }
            }
        }

        return view('master.question.form.question', $data);
    }

    private function appendAnswer(QuizAnswer $quiz_answer = null, $increment, $parent, $disabled = '')
    {
        // Jika increment tidak valid, hitung ulang berdasarkan jawaban yang sudah ada
        if (is_null($increment) || $increment <= 0) {
            $existingAnswers = QuizAnswer::where('parent_id', $parent)->count();
            $increment = $existingAnswers + 1; // Hitung jumlah jawaban + 1
        }

        $data['disabled'] = $disabled;
        $data['quiz_answer'] = $quiz_answer;
        $data['increment'] = $increment;
        $data['parent'] = $parent;
        return view('master.question.form.answer', $data);
    }


    public function store(Request $request)
    {
        try {
            DB::beginTransaction();


            if (isset($request->all_level) && $request->all_level == 'on') {
                $level = '0';
            } else {
                $level = isset($request->level) ? '|' . implode('|', $request->level) . '|' : '';
            }

            if (isset($request->all_aspect) && $request->all_aspect == 'on') {
                $aspect = '0';
            } else {
                $aspect = isset($request->aspect) ? '|' . implode('|', $request->aspect) . '|' : '';
            }


            $quiz_question = QuizQuestion::create([
                'is_random_answer' => isset($request->is_random_answer),
                'is_generate_random_answer' => isset($request->is_generate_random_answer),
                'direction_question' => $request->direction_question,
                'question' => $request->question,
                'description' => $request->description,
                'time_duration' => $request->time_duration,
                'level' => $level,
                'aspect' => $aspect
            ]);

            if ($quiz_question) {

                if ($request->hasFile('attachment')) {

                    $path = 'public/question/images' .  $quiz_question->id;
                    $path_store = 'storage/question/images' .  $quiz_question->id;

                    if (!Storage::exists($path)) {
                        // Buat directory
                        Storage::makeDirectory($path);

                        // Ubah izin folder menjadi 775 setelah membuat folder
                        $folderPath = storage_path('app/' . $path);
                        File::chmod($folderPath, 0775);  // Set izin folder menjadi 775
                    }

                    $file_name = $quiz_question->id . '-' . uniqid() . '-' . strtotime(date('Y-m-d H:i:s')) . '.' . $request->file('attachment')->getClientOriginalExtension();
                    $request->file('attachment')->storePubliclyAs($path, $file_name);
                    $attachment = $path_store . '/' . $file_name;

                    $quiz_question->update([
                        'attachment' => $attachment,
                    ]);
                }

                foreach ($request->quiz_answer as $quiz_answer_request) {
                    $add_quiz_answer = QuizAnswer::create([
                        'quiz_question_id' => $quiz_question->id,
                        'answer' => $quiz_answer_request['answer'],
                        'point' => $quiz_answer_request['point'],
                        'is_answer' => isset($quiz_answer_request['is_answer']),
                    ]);

                    if (isset($quiz_answer_request['answer_image'])) {

                        $path = 'public/answer/images' .  $add_quiz_answer->id;
                        $path_store = 'storage/answer/images' .  $add_quiz_answer->id;

                        if (!Storage::exists($path)) {
                            Storage::makeDirectory($path);
                            $folderPath = storage_path('app/' . $path);
                            File::chmod($folderPath, 0775);  // Set izin folder menjadi 775
                        }

                        $file_name = $add_quiz_answer->id . '-' . uniqid() . '-' . strtotime(date('Y-m-d H:i:s')) . '.' .  $quiz_answer_request['answer_image']->getClientOriginalExtension();
                        $quiz_answer_request['answer_image']->storePubliclyAs($path, $file_name);
                        $answer_image = $path_store . '/' . $file_name;

                        $add_quiz_answer->update([
                            'answer_image' => $answer_image,
                        ]);
                    }
                }

                DB::commit();
                return redirect()
                    ->route('master.question.index')
                    ->with(['success' => 'Berhasil Simpan Jawaban']);
            } else {
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Menambahkan Pertanyaan'])
                    ->withInput();
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()])
                ->withInput();
        }
    }

    public function show(string $id)
    {
        $data['aspects'] = AspectQuestion::whereNull('deleted_at')->get();
        $data['disabled'] = 'disabled';
        $data['quiz_question'] = QuizQuestion::find($id);

        // Inisialisasi array untuk menyimpan jawaban kuis
        $data['quiz_answer'] = [];

        if (!is_null($data['quiz_question'])) {
            foreach ($data['quiz_question']->quizAnswer as $index => $quiz_answer) {
                if (is_null($quiz_answer->deleted_at)) {
                    // Menambahkan jawaban ke dalam array quiz_answer
                    $data['quiz_answer'][] = $this->appendAnswer2($quiz_answer, $index + 1, 'disabled');
                }
            }
        }

        return view('master.question.edit', $data);
    }

    private function appendAnswer2(QuizAnswer $quiz_answer = null, $increment, $parent, $disabled = 'disabled')
    {
        $data['disabled'] = $disabled;
        $data['quiz_answer'] = $quiz_answer;
        $data['increment'] = $increment;
        $data['parent'] = $parent;
        return view('master.question.form.answer', $data);
    }


    public function edit(string $id)
    {
        $data['aspects'] = AspectQuestion::whereNull('deleted_at')->get();
        $data['disabled'] = '';
        $data['quiz_question'] = QuizQuestion::find($id);

        // Inisialisasi array untuk menyimpan jawaban kuis
        $data['quiz_answer'] = [];

        if (!is_null($data['quiz_question'])) {
            foreach ($data['quiz_question']->quizAnswer as $index => $quiz_answer) {
                if (is_null($quiz_answer->deleted_at)) {
                    // Menambahkan jawaban ke dalam array quiz_answer
                    $data['quiz_answer'][] = $this->appendAnswer($quiz_answer, $index + 1, $id);
                }
            }
        }

        return view('master.question.edit', $data);
    }

    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            $question = QuizQuestion::find($id);

            if (isset($request->all_level) && $request->all_level == 'on') {
                $level = '0';
            } else {
                $level = isset($request->level) ? '|' . implode('|', $request->level) . '|' : '';
            }

            if (isset($request->all_aspect) && $request->all_aspect == 'on') {
                $aspect = '0';
            } else {
                $aspect = isset($request->aspect) ? '|' . implode('|', $request->aspect) . '|' : '';
            }
            $question_update = QuizQuestion::where('id', $id)->update([
                'is_random_answer' => isset($request->is_random_answer),
                'is_generate_random_answer' => isset($request->is_generate_random_answer),
                'direction_question' => $request->direction_question,
                'question' => $request->question,
                'description' => $request->description,
                'time_duration' => $request->time_duration,
                'level' => $level,
                'aspect' => $aspect,
            ]);


            if ($question_update) {
                if ($request->hasFile('attachment')) {
                    $path = 'public/question/images' .  $question->id;
                    $path_store = 'storage/question/images' .  $question->id;

                    if (!Storage::exists($path)) {
                        Storage::makeDirectory($path);
                    }

                    $file_name = $question->id . '-' . uniqid() . '-' . strtotime(date('Y-m-d H:i:s')) . '.' . $request->file('attachment')->getClientOriginalExtension();

                    // Hapus file yang sudah ada jika ada
                    if (Storage::exists($path . '/' . $file_name)) {
                        Storage::delete($path . '/' . $file_name);
                    }

                    // Simpan file yang diunggah
                    $request->file('attachment')->storePubliclyAs($path, $file_name);
                    $attachment = $path_store . '/' . $file_name;

                    // Update lampiran
                    $question->update([
                        'attachment' => $attachment,
                    ]);
                }

                $last_quiz_answer = QuizQuestion::where('id', $question->id)->first()->quizAnswer->pluck('id')->toArray();

                foreach ($request->quiz_answer as $quiz_answer_request) {
                    if (isset($quiz_answer_request['id'])) {
                        $quiz_answer = QuizAnswer::where('id', $quiz_answer_request['id'])->update([
                            'quiz_question_id' => $question->id,
                            'answer' => $quiz_answer_request['answer'],
                            'answer_image' => $quiz_answer_request['answer_image'],
                            'point' => $quiz_answer_request['point'],
                            'is_answer' => isset($quiz_answer_request['is_answer']),
                        ]);

                        if (($key_answer_array = array_search($quiz_answer_request['id'], $last_quiz_answer)) !== false) {
                            unset($last_quiz_answer[$key_answer_array]);
                        }
                    } else {
                        $quiz_answer = QuizAnswer::create([
                            'quiz_question_id' => $question->id,
                            'answer' => $quiz_answer_request['answer'],
                            'answer_image' => $quiz_answer_request['answer_image'],
                            'point' => $quiz_answer_request['point'],
                            'is_answer' => isset($quiz_answer_request['is_answer']),
                        ]);
                    }

                    if (isset($quiz_answer_request['answer_image']) && $quiz_answer_request['answer_image'] instanceof UploadedFile) {

                        $path = 'public/answer/images' .  $quiz_answer->id;
                        $path_store = 'storage/answer/images' .  $quiz_answer->id;

                        if (!Storage::exists($path)) {
                            Storage::makeDirectory($path);
                        }

                        $file_name = $quiz_answer->id . '-' . uniqid() . '-' . strtotime(date('Y-m-d H:i:s')) . '.' .  $quiz_answer_request['answer_image']->getClientOriginalExtension();

                        if (Storage::exists($path . '/' . $file_name)) {
                            Storage::delete($path . '/' . $file_name);
                        }

                        $quiz_answer_request['answer_image']->storePubliclyAs($path, $file_name);
                        $answer_image = $path_store . '/' . $file_name;

                        $quiz_answer->update([
                            'answer_image' => $answer_image,
                        ]);
                    }
                    if (!$quiz_answer) {
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Update Data Pertanyaan'])
                            ->withInput();
                    }
                }

                if (!empty($last_quiz_answer)) {
                    $quiz_answer_destroy = QuizAnswer::whereIn('id', $last_quiz_answer)
                        ->update(['deleted_at' => date('Y-m-d H:i:s')]);

                    if (!$quiz_answer_destroy) {
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Hapus Jawaban'])
                            ->withInput();
                    }
                }

                DB::commit();
                return redirect()
                    ->route('master.question.index')
                    ->with(['success' => 'Berhasil Update Data Pertanyaan']);
            } else {
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Update Data Pertanyaan'])
                    ->withInput();
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()])
                ->withInput();
        }
    }



    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $question = QuizQuestion::find($id);

            if (!is_null($question)) {

                $question_deleted = QuizQuestion::where('id', $id)->update([
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);
                if ($question_deleted) {
                    DB::commit();
                    session()->flash('success', 'Berhasil Hapus Data Question');
                } else {
                    DB::rollBack();
                    session()->flash('failed', 'Gagal Hapus Data Question');
                }
            }
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()]);
        }
    }
}
