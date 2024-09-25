<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Letter;
use App\Models\ToLetter;
use App\Models\SigningAuthority;
use App\Models\ForwardCopy;
use Auth;

class LetterController extends Controller
{
    public function letter_store(Request $request){
        // Validate incoming request
        $request->validate([
            'letter_no' => 'required',
        'head_title' => 'required',
        'address' => 'required',
        'date' => 'required',
        'designation' => 'required',
        'department' => 'required',
        'dear' => 'nullable',
        'subject' => 'required',
        'draft_para' => 'required',
        'sa_name' => 'nullable',
        'sa_designation' => 'nullable',
        'sa_department' => 'nullable',
        'copy_forwarded' => 'required',
        'forwarded_copy' => 'boolean',
        ]);

        // Store the letter
        // $letter = Letter::create([
        $letter = new Letter();
            $letter->letter_no = $request->letter_no;
            $letter->head_title = $request->head_title;
            $letter->address = $request->address;
            $letter->date = $request->date;
            $letter->subject = $request->subject;
            $letter->dear = $request->dear;
            $letter->draft_para = $request->draft_para;
            $letter->is_submitted = $request->has('submit'); // Save as draft or submit
        // ]);
        $letter->user_id = auth()->id();

         // Store multiple signing authorities
         foreach ($request->signing_authorities as $signingAuthority) {
            SigningAuthority::create([
                'letter_id' => $letter->id,
                'name' => $signingAuthority['sa_name'],
                'designation' => $signingAuthority['sa_designation'],
                'department' => $signingAuthority['sa_department'],
            ]);
        }
         foreach ($request->to_letters as $to_letter) {
            ToLetter::create([
                'letter_id' => $letter->id,
                'designation' => $to_letter['designation'],
                'department' => $to_letter['department'],
                'address' => $to_letter['address'],
                'contact' => $to_letter['contact'],
            ]);
        }
         foreach ($request->forward_copies as $forward_copie) {
            ForwardCopy::create([
                'letter_id' => $letter->id,
                'copy_forwarded' => $forward_copie['copy_forwarded'],

            ]);
        }

        return redirect()->route('forms')->with('success', 'Letter saved successfully');
    }
}
