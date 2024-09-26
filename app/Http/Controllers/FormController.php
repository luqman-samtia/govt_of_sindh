<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Letter;
use App\Models\ToLetter;
use App\Models\SigningAuthority;
use App\Models\ForwardCopy;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use App\Models\Role;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Shared\Html;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;
use Dompdf\Options;
use Hash;
// use DB;



class FormController extends Controller
{
    // super admin total letters
    public function SuperAdminTotalLetter()
    {
        $totalLetters = Letter::count();
        $users_form = Letter::withCount('user')->get();
        $letters = Letter::with('user')->get();
        $draft = Letter::where('is_submitted',0)->get();
        $query = User::whereHas('roles', function ($q) {
            $q->where('name', Role::ROLE_ADMIN);
        })->with('roles')->select('users.*');
        $data['users'] = $query->count();
        return view('super_admin.total_letters',compact('letters','users_form','draft','data'));

    }

    // total user letter

    public function total_letter(Letter $letter){
        $user = Auth::user()->id;
        $users_form = Letter::where('user_id', $user)->get();
        $draft = Letter::where(['user_id'=>$user,'is_submitted'=>0])->get();

        if ($draft !== null && $draft->isNotEmpty()) {
            $total_drafts = $draft->count();
            // Do something with $total_letters
        } else {
            // Handle the case where no letters were found
            $total_drafts = 0;
        }
        if ($users_form !== null && $users_form->isNotEmpty()) {
            $total_letters = $users_form->count();
            // Do something with $total_letters
        } else {
            // Handle the case where no letters were found
            $total_letters = 0;
        }
        $user = Auth::user()->id;
        $letters = Letter::where('user_id',$user)->get();
        return view('forms.letter.single',compact('letters','total_letters','total_drafts'));
    }
    public function total_draft_letter(Letter $letter){
        $user = Auth::user()->id;
        $users_form = Letter::where('user_id', $user)->get();
        $draft = Letter::where(['user_id'=>$user,'is_submitted'=>0])->get();

        if ($draft !== null && $draft->isNotEmpty()) {
            $total_drafts = $draft->count();
            // Do something with $total_letters
        } else {
            // Handle the case where no letters were found
            $total_drafts = 0;
        }
        if ($users_form !== null && $users_form->isNotEmpty()) {
            $total_letters = $users_form->count();
            // Do something with $total_letters
        } else {
            // Handle the case where no letters were found
            $total_letters = 0;
        }
        $user = Auth::user()->id;
        $letters = Letter::where(['user_id'=>$user,'is_submitted'=>0])->get();
        return view('forms.letter.single_draft',compact('letters','total_letters','total_drafts'));
    }
    // for super admin
    public function getAllForms()
    {
            // Fetch all forms from the database
            $letters = Letter::with('user')->get(); // Assuming 'Letter' is your form model

            // Pass the forms to the view
            return view('super_admin.users_forms.index', compact('letters'));
    }




    public function index(){
        $letters = Letter::where('user_id',Auth::user()->id)->get();
        return view('forms.index' ,compact('letters'));
    }
    public function letter_create(Letter $letter){
        $user = auth()->user();
         // Get the last inserted letter to increment the letter number
    $lastLetter = Letter::latest('id')->first();
    // $lastLetter = Letter::latest('id')->first();
    // $lastLetter = DB::getPdo()->lastInsertId();
    // Set the default prefix for the letter number
    $prefix = 'ABC-GDXSC-2024-';

    // Initialize the next number based on the last letter number
    if ($lastLetter && str_contains($lastLetter->letter_no, $prefix)) {
        // Extract the number part from the last letter's number
        $lastNumber = (int) str_replace($prefix, '', $lastLetter->letter_no);
        $nextNumber = $lastNumber + 1; // Increment the number
    } else {
        $nextNumber = 1; // Start with 1 if no previous letters
    }

    // Generate the new letter number
    $newLetterNo = $prefix . $nextNumber;
        if (!$user->address || !$user->date || !$user->tel) {
            // Redirect to profile update page if profile is incomplete
            return redirect()->route('admin.dashboard')->with('error', 'Please complete your profile before creating a letter.');
        }
        return view('forms.letter.create',compact('newLetterNo'));
    }
    public function letter_store(Request $request){
        // Validate incoming request

        DB::beginTransaction();

        $request->validate([
            // 'letter_no' => 'required',
        // 'head_title' => 'required',
        // 'fix_address' => 'required',
        // 'date' => 'required',
        'designation' => 'required',
        // 'department' => 'required',
        'dear' => 'nullable',
        'subject' => 'required',
        'draft_para' => 'required',
        'sa_name' => 'nullable',
        'sa_designation' => 'nullable',
        'sa_department' => 'nullable',
        'signed_letter' => 'nullable|file|mimes:pdf|max:10240',
        'designation.*.designation' => 'required|string',
        'designation.*.department' => 'nullable|string',
        'designation.*.address' => 'nullable|string',
        'designation.*.contact' => 'nullable|string',
        // 'copy_forwarded' => 'required',
        // 'forwarded_copy' => 'boolean',
        ]);

        // Store the letter
         // Get the last inserted letter to increment the letter number
    $lastLetter = Letter::latest('id')->first();

    // Set the default prefix for the letter number
    $prefix = 'ABC-GDXSC-2024-';

    // Initialize the next number based on the last letter number
    if ($lastLetter && str_contains($lastLetter->letter_no, $prefix)) {
        // Extract the number part from the last letter's number
        $lastNumber = (int) str_replace($prefix, '', $lastLetter->letter_no);
        $nextNumber = $lastNumber + 1; // Increment the number
    } else {
        $nextNumber = 1; // Start with 1 if no previous letters
    }

    // Generate the new letter number
    $newLetterNo = $prefix . $nextNumber;


        $isSubmitted = $request->input('action') === 'submit' ? 1 : 0;
        // $route =route('Form.download.pdf', $letter->id);
        $letter = new Letter();
        $lastInsertedId = DB::getPdo()->lastInsertId();
            $letter->user_id = auth()->id();
            $letter->letter_no = $newLetterNo;
            $letter->head_title = $request->input('head_title');
            $letter->fix_address = $request->input('fix_address');
            $letter->date = Carbon::parse($request->input('date'));
            $letter->subject = $request->input('subject');
            $letter->dear = $request->input('dear');
            $letter->draft_para = $request->input('draft_para');
            $letter->is_submitted = $isSubmitted; // Save as draft or submit
            // $letter->qr_code_link = route('Form.download.pdf', $lastInsertedId); // Save as draft or submit
            $this->generateQRCode($letter);
        $letter->save();




        //  // Store multiple  designation
        //
            foreach ($request->input('designation', []) as $designation) {
                $design = new ToLetter();
                $design->letter_id = $letter->id;
                $design->designation = $designation['designation'];
                $design->department = $designation['department'];
                $design->address = $designation['address'];
                $design->contact = $designation['contact'];
                $design->save();
            }
        //

        //  // Save signing authorities
         foreach ($request->input('signing_authorities', []) as $authority) {
            $sa = new SigningAuthority();
            $sa->letter_id = $letter->id;
            $sa->name = $authority['sa_name'];
            $sa->designation = $authority['sa_designation'];
            $sa->department = $authority['sa_department'];
            $sa->save();
        }
           // Save forwarded copies
        foreach ($request->input('forwarded_copies', []) as $copy) {
            $fc = new ForwardCopy();
            $fc->letter_id = $letter->id;
            $fc->copy_forwarded = $copy['copy_forwarded'];
            $fc->save();
        }
        DB::commit();
        $message = $isSubmitted ? 'Letter submitted successfully!' : 'Letter saved as draft.';
        return redirect()->route('forms.letter.edit',$letter->id)->with('message', $message);

    }
    public function letter_edit(Letter $letter){

        if ($letter->is_submitted==1) {
            return redirect()->route('forms')->with('error', 'Submitted letters cannot be edited.');
        }
        // $letter = Letter::with('designations');
        return view('forms.letter.edit', compact('letter'));
    }


    public function letter_update(Request $request, Letter $letter)
    {
        if ($letter->is_submitted==1) {
            return redirect()->route('forms')->with('error', 'Submitted letters cannot be updated.');
        }

        DB::beginTransaction();

        try {
            $request->validate([
                // 'letter_no' => 'required',
                // 'head_title' => 'required',
                // 'fix_address' => 'required',
                // 'date' => 'required',
                'designation' => 'required',
                'subject' => 'required',
                'draft_para' => 'required',
                'designation.*.designation' => 'required|string',
                'designation.*.department' => 'nullable|string',
                'designation.*.address' => 'nullable|string',
                'designation.*.contact' => 'nullable|string',
            ]);
            $isSubmitted = $request->input('action') === 'submit' ? 1 : 0;
            $letter->update([
                // 'letter_no' => $request->input('letter_no'),
                // 'head_title' => $request->input('head_title'),
                'fix_address' => $request->input('fix_address'),
                // 'date' => Carbon::parse($request->input('date')),
                'subject' => $request->input('subject'),
                'dear' => $request->input('dear'),
                'draft_para' => $request->input('draft_para'),
                'is_submitted' => $isSubmitted,
            ]);

            // Update To Letters
            // Collect the IDs of the current designations in the request (for later deletion comparison)
$existingDesignationIds = [];

// Loop through the designations from the request
foreach ($request->input('designation', []) as $designation) {
    // Check for valid designation before creating
    if (isset($designation['designation']) && !empty($designation['designation'])) {
        if (isset($designation['id'])) {
            // Update existing record
            $toLetter = ToLetter::find($designation['id']);
            if ($toLetter) {
                $toLetter->update([
                    'designation' => $designation['designation'],
                    'department' => $designation['department'],
                    'address' => $designation['address'],
                    'contact' => $designation['contact'],
                ]);
                $existingDesignationIds[] = $designation['id']; // Collect updated IDs
            }
        } else {
            // Create new record
            $newDesignation = $letter->designations()->create([
                'designation' => $designation['designation'],
                'department' => $designation['department'],
                'address' => $designation['address'],
                'contact' => $designation['contact'],
            ]);
            $existingDesignationIds[] = $newDesignation->id; // Collect newly created ID
        }
    } else {
        \Log::warning('Skipping empty designation entry:', $designation);
    }
}

// Delete any designations not present in the request
$letter->designations()->whereNotIn('id', $existingDesignationIds)->delete();



            // Update Signing Authorities

// Collect the IDs of the current signing authorities in the request (for later deletion comparison)
$existingAuthorityIds = [];
foreach ($request->input('signing_authorities', []) as $authority) {
    if (!empty($authority['id'])) {
        // Update existing authority
        $letter->signingAuthorities()->where('id', $authority['id'])->update([
            'name' => $authority['sa_name'],
            'designation' => $authority['sa_designation'],
            'department' => $authority['sa_department'],
        ]);
        $existingAuthorityIds[] = $authority['id'];
    } else {
        // Create new authority
        $newAuthority = $letter->signingAuthorities()->create([
            'name' => $authority['sa_name'],
            'designation' => $authority['sa_designation'],
            'department' => $authority['sa_department'],
        ]);
        $existingAuthorityIds[] = $newAuthority->id;
    }
}
$letter->signingAuthorities()->whereNotIn('id', $existingAuthorityIds)->delete();
            // Update Forwarded Copies
            // old
            // $letter->forwardedCopies()->delete();
            // foreach ($request->input('forwarded_copies', []) as $copy) {
            //     $letter->forwardedCopies()->create([
            //         'copy_forwarded' => $copy['copy_forwarded'],
            //     ]);
            // }


            // Collect the IDs of the current forwarded copies in the request (for later deletion comparison)
$existingCopyIds = [];
// Loop through the forwarded copies from the request
foreach ($request->input('forwarded_copies', []) as $copy) {
    if (isset($copy['copy_forwarded']) && !empty($copy['copy_forwarded'])) {
        if (isset($copy['id'])) {
            // Update existing record
            $existingCopy = $letter->forwardedCopies()->find($copy['id']);
            if ($existingCopy) {
                $existingCopy->update([
                    'copy_forwarded' => $copy['copy_forwarded'],
                ]);
                $existingCopyIds[] = $copy['id']; // Collect updated IDs
            }
        } else {
            // Create new record
            $newCopy = $letter->forwardedCopies()->create([
                'copy_forwarded' => $copy['copy_forwarded'],
            ]);
            $existingCopyIds[] = $newCopy->id; // Collect newly created ID
        }
    } else {
        \Log::warning('Skipping empty forwarded copy entry:', $copy);
    }
}

// Delete any forwarded copies not present in the request
$letter->forwardedCopies()->whereNotIn('id', $existingCopyIds)->delete();


            DB::commit();
            $message = $isSubmitted ? 'Letter submitted & Updated successfully!' : 'Letter saved as draft & Updated.';
            return redirect()->back()->with('message', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating letter: ' . $e->getMessage());
        }
    }

    public function letter_destroy(Letter $letter)
    {
        $user = Auth::user();
        if ($letter->is_submitted==1) {
            return redirect()->route('forms')->with('error', 'Submitted letters cannot be deleted.');
        }

        DB::beginTransaction();

        try {
            $letter->designations()->delete();
            $letter->signingAuthorities()->delete();
            $letter->forwardedCopies()->delete();
            $letter->delete();

            DB::commit();
            if($user->hasRole(Role::ROLE_ADMIN)){
                return redirect()->route('forms')->with('message', 'Letter deleted successfully');

            }else{
                return redirect()->route('users.forms')->with('message', 'Letter deleted successfully');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting letter: ' . $e->getMessage());
        }
    }
    // generateQRCode

    public function generateQRCode(Letter $letter)
{

       // Generate the route for the signed letter
       $letter->save();

       // Now that the letter is saved, generate the route for the signed letter
       $filePath = storage_path('app/public/signed_letters/letter_' . $letter->id . '.pdf');

       $route = route('letters.download_signed', $letter->id);


       // Define the QR code file path
       $qrCodePath = 'qr-codes/' . $letter->id . '.png';

       // Generate the QR code with the generated route
       QrCode::format('png')
           ->size(200)
           ->generate($route, storage_path('app/public/' . $qrCodePath));

       // Save the QR code path and link in the database
       $letter->qr_code = $qrCodePath;
       $letter->qr_code_link = $route;
       $letter->save();
}



    // Pdf Generation
    public function downloadPdf(Letter $letter)
{
    // Load letter data with related models for designations, signing authorities, and forwarded copies
    $letter = $letter->load(['designations', 'signingAuthorities', 'forwardedCopies']);

    // Generate PDF from a view
    $pdf = PDF::loadView('forms.letter.pdf', compact('letter'));

     // Define the file path where the PDF will be stored
     $filePath = storage_path('app/public/downloaded_letters/letter_' . $letter->id . '.pdf');

     // Store the PDF file on the server
     $pdf->save($filePath);

     // Calculate the hash of the generated PDF
     $pdfHash = hash_file('sha256', $filePath);
     $letter->pdf_hash = $pdfHash;
     $letter->save();
    // session()->flash('redirect_url', URL::previous());
    // Return PDF for download
    return $pdf->download('letter-' . $letter->letter_no . '.pdf');



}


// check download letter qr route
public function checkDownloadRoute(Letter $letter)
{
    // Define the path where the signed letter is stored
    $filePath = storage_path('app/public/signed_letters/letter_' . $letter->id . '.pdf');

    // Check if the signed letter file exists in storage
    if (file_exists($filePath)) {
        // File exists, generate the route for downloading the signed letter
        return redirect()->route('letters.download_signed', $letter->id);
    } else {
        // File does not exist, redirect to download the original letter PDF
        return redirect()->route('Form.download.pdf', $letter->id);
    }
}

public function downloadDoc(Letter $letter)
{
    // Load letter data with related models
    $letter = $letter->load(['designations', 'signingAuthorities', 'forwardedCopies']);

    // Generate the HTML content from the view
    $htmlContent = view('forms.letter.doc-template', compact('letter'))->render();

    // Create a new instance of PhpWord
    $phpWord = new PhpWord();

    // Add a section to the document
    $section = $phpWord->addSection();

    // Add HTML content to the section
    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlContent, false, false);

    // Save the document as a Word file
    $fileName = 'letter-' . $letter->letter_no . '.docx';
    $tempFile = tempnam(sys_get_temp_dir(), $fileName);

    // Save to the temp location
    $writer = IOFactory::createWriter($phpWord, 'Word2007');
    $writer->save($tempFile);

    // Return the DOCX file as a download
    return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
}


public function uploadSignedLetter(Request $request, Letter $letter)
{
    $request->validate([
        'signed_letter' => 'required|file|mimes:pdf|max:10240', // 10MB max
    ]);
    // app/public/
    if ($request->hasFile('signed_letter')) {
        $file = $request->file('signed_letter');
        $filename = 'letter_' . $letter->id . '.pdf';
        $path = 'signed_letters/';
        $filePath = storage_path('app/public/signed_letters/letter_' . $letter->id . '.pdf');
        $file->move(storage_path('app/public/'.$path ), $filename);
         // Calculate the hash of the uploaded file
         $uploadedFileHash = hash_file('sha256', $filePath);
         if ($uploadedFileHash === $letter->pdf_hash) {
        $letter->signed_letter = $filename; // Store only the filename
        $letter->is_submitted = 1;
        $uploadedLetterPath = route('letters.download_signed', $letter->id); // Adjust this route as necessary
        $letter->qr_code_link = $uploadedLetterPath;
        $letter->save();
        return redirect()->route('admin.dashboard')->with(['message' => 'Signed letter uploaded & submitted successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Uploaded letter does not match the original letter'], 400);
        }
    }

    return redirect()->back()->with(['error' => 'No file uploaded'], 400);
}

public function updateQRCodeLink(Letter $letter)
{
    // Determine the new URL for the signed letter
    if ($letter->signed_letter !== null) {
        $newUrl = route('letters.download_signed', $letter->id);
    } else {
        $newUrl = route('Form.download.pdf', $letter->id);
    }

    // The QR code is already generated, so just update the letter to point to the new URL
    // You don't need to generate a new QR code, only update its route
    $letter->qr_code_link = $newUrl;
    $letter->save();
}


    public function downloadSignedLetter(Letter $letter)
    {
// Check if the letter has a signed letter file
            if ($letter->signed_letter) {
                $filePath = storage_path('app/public/signed_letters/' . $letter->signed_letter);
                $error = "The File you are looking for doesn't exists / isn't available / was loading incorrectly.";
                if (file_exists($filePath)) {
                    return response()->download($filePath);
                } else {
                    return redirect()->route('file.not.exist')->with($error);

                }
            }

            $message = "The File you are looking for doesn't exist because the downloaded file not uploaded yet.";

            return redirect()->route('file.not.exist')->with($message);
            }


            public function password(){
                $password = Hash::make('admin@2545');
                dd($password);
            }

            // letter preview
            public function preview($id){
                // Retrieve the letter by ID
                $letter = Letter::findOrFail($id);

                $letter = $letter->load(['designations', 'signingAuthorities', 'forwardedCopies']);

                // Pass the letter data to a preview view
                return view('forms.letter.preview', compact('letter'));
            }

            public function fileNotFound(){
                return view('errors.filenotfound');
            }
}
