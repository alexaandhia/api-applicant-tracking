<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Applicant;
use App\Models\Skill;
use App\Helpers\Apiformatter;
use Exception;

use function Laravel\Prompts\error;

class ApplicantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $applicants = Applicant::orderBy('id', 'asc')->get();
        return response()->json($applicants);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $resumeFile = $request->file('resume');

    if ($resumeFile) {
        // Jika ada file yang diunggah, simpan file
        $docs = rand() . '.' . $resumeFile->extension();
        $path = public_path('assets/resume/');
        $resumeFile->move($path, $docs);
        $resumeUrl = url('assets/resume/' . $docs);

            $applicant = new Applicant;
            $applicant->first_name = $request->first_name;
            $applicant->last_name = $request->last_name;
            $applicant->title = $request->title;
            $applicant->description = $request->description;
            $applicant->department = $request->department;
            $applicant->experience = $request->experience;
            $applicant->phone = $request->phone;
            $applicant->email = $request->email;
            $applicant->resume = $resumeUrl;
            $applicant->employer = $request->employer;
            $applicant->position = $request->position;
            $applicant->applied = $request->applied;
            $applicant->interview = $request->interview;
            $applicant->interviewer = $request->interviewer;
            $applicant->score = $request->score;
            $applicant->status = $request->status;
            $applicant->notes = $request->notes;
            $skills = $request->skill;
            foreach ($skills as $skillId) {
                // Cek apakah skill dengan ID tersebut ada di database
                $skill = Skill::find($skillId);
                if ($skill) {
                    // Jika ada, simpan ke tabel pivot "applicant_skills"
                    $applicant->skills()->attach($skillId);
                }
            }            
            $applicant->save();
            return response()->json($applicant);
            } else {
            return response()->json(['message' => 'File not uploaded.']);
            }
            

    }

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
    public function update(Request $request, string $id)
    {
        $pdf = $request->file('resume');

if ($pdf) {
    $docs = rand() . '.' . $pdf->extension();
    $path = public_path('assets/resume/');
    $pdf->move($path, $docs);
    // ...
} else {
    response()->json(error(''));
}

        $applicant = Applicant::findOrFail($id);
        $applicant->first_name = $request->first_name;
        $applicant->last_name = $request->last_name;
        $applicant->title = $request->title;
        $applicant->description = $request->description;
        $applicant->department = $request->department;
        $applicant->experience = $request->experience;
        $applicant->phone = $request->phone;
        $applicant->email = $request->email;
        $applicant->resume = $pdf;
        $applicant->employer = $request->employer;
        $applicant->position = $request->position;
        $applicant->applied = $request->applied;
        $applicant->interview = $request->interview;
        $applicant->interviewer = $request->interviewer;
        $applicant->score = $request->score;
        $applicant->status = $request->status;
        $applicant->notes = $request->notes;
        
        $applicant->update();
        return response()->json($applicant);
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
