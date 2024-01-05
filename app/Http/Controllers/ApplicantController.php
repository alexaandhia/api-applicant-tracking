<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Applicant;
use App\Models\Skill;
use Illuminate\Validation\Rule;
use App\Models\ApplicantSkill;
use Exception;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\error;

class ApplicantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     $query = Applicant::query();

    //     if (isset($request->department) && ($request->department != null)) {
    //         $query->where('department', $request->department);
    //     }
    //     if (isset($request->skill) && is_array($request->skill) && count($request->skill) > 0) {
    //         $query->whereHas('skills', function ($q) use ($request) {
    //             $q->whereIn('skills.id', $request->skill);
    //         });
    //     }

    //     $applicants = $query->orderBy('id', 'asc')->get();

    //     return response()->json($applicants);
    // }
    public function index(Request $request)
{
    $query = Applicant::query();

    if (isset($request->department) && ($request->department != null)) {
        $query->where('department', $request->department);
    }
    if (isset($request->skill) && is_array($request->skill) && count($request->skill) > 0) {
        $query->whereHas('skills', function ($q) use ($request) {
            $q->whereIn('skills.id', $request->skill);
        });
    }

    $applicants = $query->with('skills')->orderBy('id', 'asc')->get();

    return response()->json($applicants);
}


    public function token()
    {
        return csrf_token();
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function search(Request $request)
    // {
    
    //     dd($request->all());
    //     $department = $request->input('department');
    //     $skills = $request->input('skill');

    //     $query = Applicant::query();

    //     if ($department) {
    //         $query->where('department', $department);
    //     }

    //     if ($skills) {
    //         $skills = Skill::all();
    //         $skillIds = $skills->pluck('id')->toArray(); // Extract skill IDs

    //         $query->whereHas('skills', function ($q) use ($skillIds) {
    //             $q->whereIn('skills.id', $skillIds);
    //         });
    //     }

    //     $applicants = $query->with('skills')->paginate(5);

    //     return response()->json(['results' => $applicants]);
    // }

    public function search(Request $request)
    {
        $department = $request->input('department');
        $skills = $request->input('skills');
    
        $query = Applicant::query();
    
        if ($department) {
            $query->where('department', $department);
        }
    
        if ($skills) {
            // Assuming $skills is an array of skill IDs
            $query->whereHas('skills', function ($q) use ($skills) {
                $q->whereIn('id', $skills); // Use 'id' directly, assuming 'skills' table has 'id' column
            });
        }
    
        $applicants = $query->with('skills')->get();
    
        return response()->json($applicants);
    }
    
    
    public function create()
    {
        //
    }

    public function store(Request $request)
{
    try {
        $applicant = new Applicant;

        $resumeFile = $request->file('resume');
        if ($resumeFile) {
            $docs = rand() . '.' . $resumeFile->extension();
            $path = public_path('assets/resume/');
            $resumeFile->move($path, $docs);
            $resumeUrl = url('assets/resume/' . $docs);
            $applicant->resume = $resumeUrl;
        }

        $applicant->fill($request->except(['resume', 'skill']));

        $applicant->save();

        $applicantId = $applicant->id;

        $skills = $request->input('skill', []);

        if (!empty($skills) && is_array($skills)) {
            // Attach skills to the applicant
            $applicant->skills()->attach($skills);
        }

        return response()->json($applicant);
    } catch (\Exception $error) {
        return response()->json(['error' => $error->getMessage()], 500);
    }
}

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $applicant = new Applicant;
    
    //     $resumeFile = $request->file('resume');
    
    //     if ($resumeFile) {
    //         // Jika ada file yang diunggah, simpan file
    //         $docs = rand() . '.' . $resumeFile->extension();
    //         $path = public_path('assets/resume/');
    //         $resumeFile->move($path, $docs);
    //         $resumeUrl = url('assets/resume/' . $docs);
    
    //         // Set atribut aplikasi hanya jika ada file yang diunggah
    //         $applicant->resume = $resumeUrl;
    //     }
    
    //     // Set atribut aplikasi dari permintaan
    //     $applicant->first_name = $request->first_name;
    //     $applicant->last_name = $request->last_name;
    //     $applicant->title = $request->title;
    //     $applicant->description = $request->description;
    //     $applicant->department = $request->department;
    //     $applicant->experience = $request->experience;
    //     $applicant->phone = $request->phone;
    //     $applicant->email = $request->email;
    //     $applicant->employer = $request->employer;
    //     $applicant->position = $request->position;
    //     $applicant->applied = $request->applied;
    //     $applicant->interview = $request->interview;
    //     $applicant->interviewer = $request->interviewer;
    //     $applicant->score = $request->score;
    //     $applicant->status = $request->status;
    //     $applicant->notes = $request->notes;
    
    //     $skills = $request->skill;
    
    //     // Simpan aplikasi ke database
    //     $applicant->save();
    
    //     // Lampirkan keterampilan jika disediakan
    //     if (!is_null($skills) && is_array($skills)) {
    //         $applicant->skills()->attach($skills);
    //     }
    
    //     return response()->json($applicant);
    // }

//     public function store(Request $request)
// {


//     $applicant = new Applicant;

//     $resumeFile = $request->file('resume');
//     if ($resumeFile) {
//         $docs = rand() . '.' . $resumeFile->extension();
//         $path = public_path('assets/resume/');
//         $resumeFile->move($path, $docs);
//         $resumeUrl = url('assets/resume/' . $docs);
//         $applicant->resume = $resumeUrl;
//     }

//     try {
//         $applicant->fill($request->except(['resume', 'skill']));

//         $applicant->save();

//         $applicantId = $applicant->id;

//         $skills = $request->input('skill', []);

//         if (!empty($skills) && is_array($skills)) {
//             foreach ($skills as $skillId) {
//                 $applicant->skills()->attach($skillId, ['applicant_id' => $applicantId]);
//             }
//         }

//         return response()->json($applicant);
//     } catch (\Exception $error) {
//         return response()->json(['error' => $error->getMessage()], 500);
//     }
// }


// public function store(Request $request)
//     {
//         $request->validate([
//             'first_name' => 'required',
//             'last_name' => 'required',
//             'phone' => 'required',
//             'email' => 'required',
//             'resume' => 'required|mimes:pdf', //pdf
//             'skill' => 'required',
//         ]);

//         $pdf = $request->file('resume');
//         $docs = rand() . '.' . $pdf->extension();
//         $path = public_path('assets/resume/');
//         $pdf->move($path, $docs);
//         // yang resume jangan lupa diubah

//         try {
//             $applicant = Applicant::create([
//                 'first_name' => $request->first_name,
//                 'last_name' => $request->last_name,
//                 'title' => $request->title,
//                 'description' => $request->description,
//                 'department' => $request->department,
//                 'experience' => $request->experience,
//                 'phone' => $request->phone,
//                 'email' => $request->email,
//                 'resume' => $docs,
//                 'employer' => $request->employer,
//                 'position' => $request->position,
//                 'applied' => $request->applied,
//                 'interview' => $request->interview,
//                 'interviewer' => $request->interviewer,
//                 'score' => $request->score,
//                 'status' => $request->status,
//                 'notes' => $request->notes,
//             ]);
//             $skills = $request->skill;
//             foreach ($skills as $skillId) {
//                 // Cek apakah skill dengan ID tersebut ada di database
//                 $skill = Skill::find($skillId);
//                 if ($skill) {
//                     // Jika ada, simpan ke tabel pivot "applicant_skills"
//                     $applicant->skills()->attach($skillId);
//                 }
//             }


//             return redirect('/data')->with('success', 'Data Added');
//         } catch (\Exception $error) {
//             return redirect()->back()->with('errorAdd', $error->getMessage());
//         }
//     }
    
    

    public function addSkill()
    {

        $skills = Skill::get();
        return response()->json($skills);
    }

    public function save(Request $request)
    {


        $skill = new Skill;
        $skill->skill = $request->skill;
        $skill->save();
        return response()->json($skill);
    }

//     public function save(Request $request)
// {
//     // Validasi request
//     $request->validate([
//         'skill' => [
//             'required',
//             'string',
//             Rule::unique('skills', 'skill'), // Memastikan skill unik di dalam tabel 'skills'
//         ],
//     ]);

//     $skill = new Skill;
//     $skill->skill = $request->skill;
//     $skill->save();

//     return response()->json($skill);
// }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $applicant = Applicant::findOrFail($id);
        return response()->json($applicant);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    try {
        $applicant = Applicant::findOrFail($id);

        $resumeFile = $request->file('resume');
        if ($resumeFile) {
            $docs = rand() . '.' . $resumeFile->extension();
            $path = public_path('assets/resume/');
            $resumeFile->move($path, $docs);
            $resumeUrl = url('assets/resume/' . $docs);
            $applicant->resume = $resumeUrl;
        }

        $applicant->fill($request->except(['resume', 'skill']));

        $applicant->save();

        $skills = $request->input('skill', []);

        // Detach existing skills
        $applicant->skills()->detach();

        if (!empty($skills) && is_array($skills)) {
            // Attach updated skills to the applicant
            $applicant->skills()->attach($skills);
        }

        return response()->json($applicant);
    } catch (\Exception $error) {
        return response()->json(['error' => $error->getMessage()], 500);
    }
}


    /**
     * Remove the specified resource from storage.
     */


    public function destroy(string $id)
    {
        $applicant = Applicant::find($id);

        if (!$applicant) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        // unlink('assets/resume/' . $applicant->resume);
        $applicant->delete();

        return response()->json($applicant);
    }
}
