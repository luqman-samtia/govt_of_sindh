<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Letter;
use App\Models\ToLetter;
use App\Models\SigningAuthority;
use App\Models\ForwardCopy;
use App\Models\User;
use App\Models\Order;
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
use PhpOffice\PhpWord\Settings;
use PHPHtmlToDoc\PHPHtmlToDoc;
use HTMLPurifier;
use HTMLPurifier_Config;
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Cell;
use PhpOffice\PhpWord\Style\Font;
// use PhpOffice\PhpWord\Shared\Html;

function html2text($html) {
    // Replace <br> and <p> tags with newlines
    $text = preg_replace('/<br\s*\/?>/i', "\n", $html);
    $text = preg_replace('/<\/p>/i', "\n\n", $text);

    // Convert <b>, <strong>, <i>, <em>, <u> to their plain text equivalents
    $text = preg_replace('/<(b|strong)>(.*?)<\/(b|strong)>/i', '*$2*', $text);
    $text = preg_replace('/<(i|em)>(.*?)<\/(i|em)>/i', '_$2_', $text);
    $text = preg_replace('/<u>(.*?)<\/u>/i', '$1', $text);

    // Remove all remaining HTML tags
    $text = strip_tags($text);

    // Decode HTML entities
    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    // Remove extra whitespace
    $text = preg_replace('/\s+/', ' ', $text);
    $text = trim($text);

    return $text;
}



class FormController extends Controller
{
    // super admin total letters
    public function SuperAdminTotalLetter()
    {
        $totalLetters = Letter::count();
        $users_form = Letter::withCount('user')->get();
        $letters = Letter::with('user')->where('is_submitted',1)->orderBy('id', 'desc')->get();
        $orders = Order::with('user')->where('is_submitted',1)->orderBy('id', 'desc')->get();
        $draft = Letter::where('is_submitted',0)->get();
        $draft_order = Order::where('is_submitted',0)->get();
        $query = User::whereHas('roles', function ($q) {
            $q->where('name', Role::ROLE_ADMIN);
        })->with('roles')->select('users.*');
        $data['users'] = $query->count();
        return view('super_admin.total_letters',compact('letters','users_form','draft','data','draft_order','orders'));

    }
    public function SuperAdminTotalOrder()
    {
        $totalLetters = Letter::count();
        $users_form = Letter::withCount('user')->get();
        $letters = Order::with('user')->where('is_submitted',1)->orderBy('id', 'desc')->get();
        $letter = Letter::with('user')->where('is_submitted',1)->orderBy('id', 'desc')->get();
        $orders = Order::with('user')->where('is_submitted',1)->orderBy('id', 'desc')->get();
        $draft = Letter::where('is_submitted',0)->get();
        $draft_order = Order::where('is_submitted',0)->get();
        $query = User::whereHas('roles', function ($q) {
            $q->where('name', Role::ROLE_ADMIN);
        })->with('roles')->select('users.*');
        $data['users'] = $query->count();
        return view('super_admin.total_orders',compact('letters','users_form','draft','data','draft_order','orders','letter'));

    }
    // search Letters



    public function SuperAdminTotalDraftLetter()
    {
        $totalLetters = Letter::count();
        $users_form = Letter::withCount('user')->where('is_submitted',1)->get();
        $users_order_form = Order::withCount('user')->where('is_submitted',1)->get();
        // $letters = Letter::where('is_submitted',0)->get();
        $letters = Letter::with('user')
            ->where('is_submitted', 0)
            ->orderBy('id', 'desc')
            ->get();
        $orders_draft = Order::with('user')
            ->where('is_submitted', 0)
            ->orderBy('id', 'desc')
            ->get();
        // $draft = Letter::where('is_submitted',0)->get();
        $query = User::whereHas('roles', function ($q) {
            $q->where('name', Role::ROLE_ADMIN);
        })->with('roles')->select('users.*');
        $data['users'] = $query->count();
        return view('super_admin.total_draft',compact('letters','users_form','data','users_order_form','orders_draft'));

    }
    public function SuperAdminTotalDraftOrder()
    {
        $totalLetters = Letter::count();
        $users_form = Letter::withCount('user')->where('is_submitted',1)->get();
        $users_order_form = Order::withCount('user')->where('is_submitted',1)->get();
        // $letters = Letter::where('is_submitted',0)->get();
        $letters = Order::with('user')
            ->where('is_submitted', 0)
            ->orderBy('id', 'desc')
            ->get();
        $orders_draft = Letter::with('user')
            ->where('is_submitted', 0)
            ->orderBy('id', 'desc')
            ->get();
        // $draft = Letter::where('is_submitted',0)->get();
        $query = User::whereHas('roles', function ($q) {
            $q->where('name', Role::ROLE_ADMIN);
        })->with('roles')->select('users.*');
        $data['users'] = $query->count();
        return view('super_admin.total_draft_order',compact('letters','users_form','data','users_order_form','orders_draft'));

    }

    // total user letter

    public function total_letter(Letter $letter){
        $user = Auth::user()->id;
        $users_form = Letter::where(['user_id'=>$user,'is_submitted'=>1])->get();
        $draft = Letter::where(['user_id'=>$user,'is_submitted'=>0])->get();
        $users_order = Order::where(['user_id'=>$user,'is_submitted'=>1])->get();
        $users_draft_order = Order::where(['user_id'=>$user,'is_submitted'=>0])->get();

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
        $letters = Letter::where(['user_id'=>$user,'is_submitted'=>1])->orderBy('id', 'desc')->get();
        return view('forms.letter.single',compact('letters','total_letters','total_drafts','users_order','users_draft_order'));
    }
    public function total_draft_letter(Letter $letter){
        $user = Auth::user()->id;
        $users_form = Letter::where(['user_id'=>$user,'is_submitted'=>1])->get();
        $draft = Letter::where(['user_id'=>$user,'is_submitted'=>0])->get();
        $draft_order = Order::where(['user_id'=>$user,'is_submitted'=>0])->get();
        $users_order = Order::where(['user_id'=>$user,'is_submitted'=>1])->get();
        $users_draft_order = Order::where(['user_id'=>$user,'is_submitted'=>0])->get();


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
        $letters = Letter::where(['user_id'=>$user,'is_submitted'=>0])->orderBy('id', 'desc')->get();
        return view('forms.letter.single_draft',compact('letters','total_letters','total_drafts','users_order','draft_order','users_draft_order'));
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
    public function letter_create(Letter $letter, User $user){
        $user = auth()->user();

    // $lastLetter = Letter::latest('id')->first();

    $prefix = $user->letter_no; // Assuming this field exists in the user table

    // Fetch the latest letter for this user based on their prefix
    $lastLetter = Letter::where('letter_no', 'LIKE', "{$prefix}%")
                        ->where('user_id', $user->id)
                        ->latest('letter_no')
                        ->first();

    // Initialize the next number based on the last letter number
    if ($lastLetter && str_contains($lastLetter->letter_no, $prefix)) {
        // Extract the number part from the last letter's number
        $lastNumber = (int) str_replace($prefix, '', $lastLetter->letter_no);
        $nextNumber = $lastNumber + 1; // Increment the number
    } else {
        $nextNumber = 1; // Start with 1 if no previous letters
    }

    // Generate the new letter number for this user
    $newLetterNo = $prefix . $nextNumber;

        if (!$user->address || !$user->date || !$user->tel) {
            // Redirect to profile update page if profile is incomplete
            return redirect()->route('admin.dashboard')->with('error', 'Please complete your profile before creating a letter.');
        }
        // return view('forms.letter.create',compact('newLetterNo'));
        return response()->view('forms.letter.create',compact('newLetterNo'));

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
    // $lastLetter = Letter::latest('id')->first();
    $user = auth()->user();
    $prefix = $user->letter_no; // Assuming this field exists in the user table

    // Fetch the latest letter for this user based on their prefix
    $lastLetter = Letter::where('letter_no', 'LIKE', "{$prefix}%")
                        ->where('user_id', $user->id)
                        ->latest('letter_no')
                        ->first();

    // Initialize the next number based on the last letter number
    if ($lastLetter && str_contains($lastLetter->letter_no, $prefix)) {
        // Extract the number part from the last letter's number
        $lastNumber = (int) str_replace($prefix, '', $lastLetter->letter_no);
        $nextNumber = $lastNumber + 1; // Increment the number
    } else {
        $nextNumber = 1; // Start with 1 if no previous letters
    }

    // Generate the new letter number for this user
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
            $sa->other = $authority['sa_other'];
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
            return redirect()->route('admin.dashboard')->with('error', 'Submitted letters cannot be edited.');
        }
        // $letter = Letter::with('designations');
        return view('forms.letter.edit', compact('letter'));
    }


    public function letter_update(Request $request, Letter $letter)
    {
        if ($letter->is_submitted==1) {
            return redirect()->route('admin.dashboard')->with('error', 'Submitted letters cannot be updated.');
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
            'other' => $authority['sa_other'],
        ]);
        $existingAuthorityIds[] = $authority['id'];
    } else {
        // Create new authority
        $newAuthority = $letter->signingAuthorities()->create([
            'name' => $authority['sa_name'],
            'designation' => $authority['sa_designation'],
            'department' => $authority['sa_department'],
            'other' => $authority['sa_other'],
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
        // $user = Auth::user();
        // if ($letter->is_submitted==1) {
        //     return redirect()->back()->with('error', 'Submitted letters cannot be deleted.');
        // }

        DB::beginTransaction();

        try {
            $letter->designations()->delete();
            $letter->signingAuthorities()->delete();
            $letter->forwardedCopies()->delete();
            $letter->delete();

            DB::commit();

                return redirect()->back()->with('message', 'Letter deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting letter: ' . $e->getMessage());
        }
    }
    public function order_destroy(Order $letter)
    {
        // $user = Auth::user();
        // if ($letter->is_submitted==1) {
        //     return redirect()->back()->with('error', 'Submitted letters cannot be deleted.');
        // }

        DB::beginTransaction();

        try {
            $letter->designations()->delete();
            $letter->signingAuthorities()->delete();
            $letter->forwardedCopies()->delete();
            $letter->delete();

            DB::commit();

                return redirect()->back()->with('message', 'Order deleted successfully');

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
       $route = route('letters.view', $letter->id);
    //    file_exists
       // Now that the letter is saved, generate the route for the signed letter
    //    $filePath = storage_path('app/public/downloaded_letters/letter_' . $letter->id . '.pdf');
    //    $filePath = storage_path('app/public/signed_letters/letter_' . $letter->id . '.pdf');
    //    if ($letter->is_submitted==0) {
    //     // Set the route to the uploaded file instead of generating a new one
    //     $route = url('/letter/'.$letter->id.'/download-pdf');
    // } else {
    //     // If no uploaded file, generate a route for downloading the signed letter
    //     $route =route('letters.download_signed', $letter->id);
    // }
    //    $route = route('letters.download_signed', $letter->id);
    //    $route = route('Form.download.pdf', $letter->id);

    // letter/{letter}/download-pdf
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


// Letter view on scan
    public function view(Letter $letter)
{
    $filePath = storage_path('app/public/signed_letters/letter_' . $letter->letter_no . '.pdf');

if ($letter->is_submitted == 1 && file_exists($filePath)) {
    // If the letter is submitted and the signed letter file exists, download it directly
    return response()->download($filePath);
} else {
    // If the letter is not submitted or doesn't have a signed version, download the original letter
    $originalLetterPath = storage_path('app/public/downloaded_letters/letter_' . $letter->letter_no . '.pdf');
    if (file_exists($originalLetterPath)) {
        return response()->download($originalLetterPath);
    } else {
        return redirect()->route('Form.download.pdf',$letter->id);
    }
}
}

    // Pdf Generation
    public function downloadPdf(Letter $letter)
{
    // Load letter data with related models for designations, signing authorities, and forwarded copies
    $letter = $letter->load(['designations', 'signingAuthorities', 'forwardedCopies']);

    // Generate PDF from a view
    $pdf = PDF::loadView('forms.letter.pdf', compact('letter'));
    $letter_no = $letter->letter_no;
    if (strpos($letter_no, '/') !== false) {
        // Replace all slashes with underscores
        $letter_no = str_replace('/', '_', $letter_no);
    }
      $fileName = 'letter_' . $letter_no . '.pdf';
     // Define the file path where the PDF will be stored
     $filePath = storage_path('app/public/downloaded_letters/'. $fileName);

     // Store the PDF file on the server
     $pdf->save($filePath);

     // Calculate the hash of the generated PDF
     $pdfHash = hash_file('sha256', $filePath);
     $letter->pdf_hash = $fileName;
     $letter->save();
    // session()->flash('redirect_url', URL::previous());
    // Return PDF for download
    return $pdf->download($fileName);



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
    $letter = Letter::with(['user', 'designations', 'signingAuthorities', 'forwardedCopies'])->findOrFail($letter->id);

    $phpWord = new PhpWord();
    $phpWord->setDefaultFontName('Times New Roman');
    $phpWord->setDefaultFontSize(11);

    $section = $phpWord->addSection();


    $fontStyle = [
        'name' => 'Times New Roman',
        'size' => 11,
        'bold' => false
    ];
    // Header table
    // $table = $section->addTable();
    // $table->addRow();

    $table = $section->addTable([
        'width' => 100 * 50,
        'unit' => 'pct',
        'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::START
    ]);
    $table->addRow();

    // Logo cell (left side)
    $cell1 = $table->addCell(2000); // Adjust width as needed
    $cell1->addImage(storage_path('app/public/qr-codes/download.png'), [
        'width' => 100,
        'height' => 50,
        'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START
    ]);

    // Content cell (right side)
    $cell2 = $table->addCell(8000,[ 'text-align' => 'center']); // Adjust width as needed
    $headerStyle = [
        'name' => 'Times New Roman',
        'bold' => true,
        'size' => 11,
        // 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
    ];
    $paragraphStyle = [
        'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, // Center alignment
        'spaceAfter' => 0,
        'spaceBefore' => 0,
        'lineHeight' => 1,
        'name' => 'Times New Roman',
    ];
    // $cell2->getStyle()->setMargins(0, 0, 0, 10);
    // $cell2->addText(html2text($letter->letter_no), $headerStyle,$paragraphStyle);
    $cell2->addText('GOVERNMENT OF SINDH', $headerStyle,$paragraphStyle);
    $cell2->addText('ANTI-CORRUPTION ESTABLISHMENT', $headerStyle,$paragraphStyle);

    if (strtolower($letter->user->designation) == 'chairman') {
        $cell2->addText('Chairman Office', $headerStyle,$paragraphStyle);
    } elseif (strtolower($letter->user->designation) == 'director') {
        $cell2->addText('HEAD QUATOR', $headerStyle,$paragraphStyle);
    } else {
        $cell2->addText($letter->head_title, $headerStyle, $paragraphStyle);
    }
    $normalStyle = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
    'name' => 'Times New Roman',
    ];
    $cell2->addText(html2text($letter->user->address), $normalStyle,$paragraphStyle);
    $cell2->addText("Phone No: {$letter->user->contact}, Fax: {$letter->user->tel}", $normalStyle,$paragraphStyle);

$headerTable = $section->addTable([
    'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
]);

$headerTable->addRow();
$cell1 = $headerTable->addCell(5000); // Adjust the width as needed
$cell2 = $headerTable->addCell(5000, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]);

// Add letter_no in the left cell
$headerStyle = [
    'bold' => false,
    'size' => 11,
];
$cell1->addText(html2text($letter->letter_no), $headerStyle);

// Add the date in the right cell
$rightAlignStyle = [
    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END, // Right alignment for date
    'bold' => true,
];
$cell2->addText("Dated: the " . date('dS M, Y', strtotime($letter->date)), ['bold'=>true], $rightAlignStyle);
    $paragraphStyless = [
        'name' => 'Times New Roman',
        'spaceAfter' => 0,
        'spaceBefore' => 0,
        'lineHeight' => 1,
    ];
    $cellStyle = [
        // 'valign' => 'center', // Vertical alignment (if needed)
        'spaceBefore' => 200 // Adjust the margin top (in twips)
    ];

    $toTable = $section->addTable();
    $toTable->addRow();
    $toCell1 = $toTable->addCell(1000);
    $toCell1->addText('To,', ['bold' => true]);

    $toCell2 = $toTable->addCell(9000);
    $toCell2->addTextBreak();

    foreach ($letter->designations as $toLetter) {
        $toCell2->addText($toLetter->designation, ['bold' => true],$paragraphStyless);
        $toCell2->addText($toLetter->department,[],$paragraphStyless);
        $toCell2->addText($toLetter->address,[],$paragraphStyless);
        if (!empty($toLetter->contact)) {
            $toCell2->addText($toLetter->contact,[],$paragraphStyless);
        }
        $toCell2->addTextBreak();
    }

    // Subject
    $subjectTable = $section->addTable();
    $subjectTable->addRow();
    $subjectCell1 = $subjectTable->addCell(1000);
    $subjectCell1->addText('Subject:', ['bold' => true, 'name' => 'Times New Roman']);
    $subjectCell2 = $subjectTable->addCell(9000);
    $subjectCell2->addText(strtoupper($letter->subject), ['bold' => true, 'underline' => 'single', 'name' => 'Times New Roman']);

    // Main content
    // $textrun = $section->addTextRun();
    // $indentStyle = [
    //     'indentation' => ['left' => 720], // Set left indentation (720 twips = 0.5 inch)
    // ];
    $plainText = html2text($letter->draft_para);

    // Split the text into paragraphs
    $paragraphs = explode("\n\n", $plainText);


foreach ($paragraphs as $paragraph) {
    // Trim the paragraph to remove any leading/trailing whitespace
    $paragraph = trim($paragraph);

    // Skip empty paragraphs
    if (empty($paragraph)) {
        continue;
    }

    // Add each paragraph with indentation for the first line
    $section->addText($paragraph, null, [
        'indentation' => [
            'firstLine' => 920, // 0.5 inch indentation (720 twips)
            'name' => 'Times New Roman',
        ],
        'spacing' => [
            'after' => 0, // No extra spacing after the paragraph
            'name' => 'Times New Roman',
        ],
    ]);

    // Add a line break after each paragraph
    $section->addTextBreak(1, null, ['spacing' => ['after' => 0]]);
}

    // Signature and QR code
    $signatureTable = $section->addTable();
    $signatureTable->addRow();
    $qrCell = $signatureTable->addCell(5000);
    $qrCell->addImage(storage_path('app/public/' . $letter->qr_code), [
        'width' => 50,
        'height' => 50,
    ]);

    $centerAlignStyle = [
        'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, // Center alignment
        'spaceAfter' => 0,
         'spaceBefore' => 0,
         'lineHeight' => 1,
         'name' => 'Times New Roman',
    ];

    // Add content to the signing authority cell with center alignment
    $signCell = $signatureTable->addCell(5000);
    foreach ($letter->signingAuthorities as $authority) {
        $signCell->addText($authority->name, ['bold' => true], $centerAlignStyle); // Name with bold and center alignment
        $signCell->addText($authority->designation, [], $centerAlignStyle); // Designation with center alignment
        $signCell->addText("For {$authority->department}", [], $centerAlignStyle); // Department with center alignment
        $signCell->addText('0301-2255945', [], $centerAlignStyle); // Phone number with center alignment
        $signCell->addTextBreak(); // Line break
    }

    // Forwarded copies
    $section->addText('A copy is forwarded for similar compliance:-', ['bold' => true]);
    $listStyle = [
        'listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER,
        'spaceAfter' => 0,
         'spaceBefore' => 0,
         'name' => 'Times New Roman',

    ];
    $listStyleType = [
        'spaceAfter' => 0,
         'spaceBefore' => 0,
         'name' => 'Times New Roman',

    ];
    foreach ($letter->forwardedCopies as $forward) {
        $section->addListItem($forward->copy_forwarded, 0, null, $listStyle,$listStyleType);
    }

    // Save and download
    $letter_no = $letter->letter_no;
    if (strpos($letter_no, '/') !== false) {
        // Replace all slashes with underscores
        $letter_no = str_replace('/', '_', $letter_no);
    }
    $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
    $fileName = 'letter_' . $letter_no. '.docx';
    $tempFile = tempnam(sys_get_temp_dir(), $fileName);
    $objWriter->save($tempFile);

    return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
}

// Order Doc Download
public function downloadDocOrder(Order $letter)
{
    $letter = Order::with(['user', 'designations', 'signingAuthorities', 'forwardedCopies'])->findOrFail($letter->id);

    $phpWord = new PhpWord();
    $phpWord->setDefaultFontName('Times New Roman');
    $phpWord->setDefaultFontSize(11);

    $section = $phpWord->addSection();


    $fontStyle = [
        'name' => 'Times New Roman',
        'size' => 11,
        'bold' => false
    ];
    // Header table
    // $table = $section->addTable();
    // $table->addRow();

    $table = $section->addTable([
        'width' => 100 * 50,
        'unit' => 'pct',
        'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::START
    ]);
    $table->addRow();

    // Logo cell (left side)
    $cell1 = $table->addCell(2000); // Adjust width as needed
    $cell1->addImage(storage_path('app/public/qr-codes/download.png'), [
        'width' => 100,
        'height' => 50,
        'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START
    ]);

    // Content cell (right side)
    $cell2 = $table->addCell(9000,[ 'text-align' => 'center']); // Adjust width as needed
    $headerStyle = [
        'name' => 'Times New Roman',
        'bold' => true,
        'size' => 11,
        // 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
    ];
    $paragraphStyle = [
        'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, // Center alignment
        'spaceAfter' => 0,
        'spaceBefore' => 0,
        'lineHeight' => 1,
        'name' => 'Times New Roman',
    ];
    // $cell2->getStyle()->setMargins(0, 0, 0, 10);
    // $cell2->addText(html2text($letter->letter_no), $headerStyle,$paragraphStyle);
    $cell2->addText('GOVERNMENT OF SINDH', $headerStyle,$paragraphStyle);
    $cell2->addText('ANTI-CORRUPTION ESTABLISHMENT', $headerStyle,$paragraphStyle);

    if (strtolower($letter->user->designation) == 'chairman') {
        $cell2->addText('Chairman Office', $headerStyle,$paragraphStyle);
    } elseif (strtolower($letter->user->designation) == 'director') {
        $cell2->addText('HEAD QUATOR', $headerStyle,$paragraphStyle);
    } else {
        $cell2->addText($letter->head_title, $headerStyle, $paragraphStyle);
    }
    $normalStyle = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
    'name' => 'Times New Roman',
    ];
    $cell2->addText(html2text($letter->user->address), $normalStyle,$paragraphStyle);
    $cell2->addText("Phone No: {$letter->user->contact}, Fax: {$letter->user->tel}", $normalStyle,$paragraphStyle);




    $paragraphStyless = [
        'name' => 'Times New Roman',
        'spaceAfter' => 0,
        'spaceBefore' => 0,
        'lineHeight' => 1,
    ];
    $cellStyle = [
        // 'valign' => 'center', // Vertical alignment (if needed)
        'spaceBefore' => 200 // Adjust the margin top (in twips)
    ];



    // Subject
    $subjectTable = $section->addTable();
    $subjectTable->addRow();
    $subjectCell1 = $subjectTable->addCell(1000);
    $subjectCell1->addText('O R D E R',
     ['bold' => true, 'name' => 'Times New Roman','underline' => 'single'],
     [
        'spaceBefore' => 1100, // Adds space before the text (value in twips, 300 = 0.2 inch)
        'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT // Keeps the alignment to the left
    ]

    );
    // $subjectCell2 = $subjectTable->addCell(9000);
    // $subjectCell2->addText(strtoupper($letter->subject), ['bold' => true, 'underline' => 'single', 'name' => 'Times New Roman']);

    // Main content
    // $textrun = $section->addTextRun();
    // $indentStyle = [
    //     'indentation' => ['left' => 720], // Set left indentation (720 twips = 0.5 inch)
    // ];
    $plainText = html2text($letter->draft_para);

    // Split the text into paragraphs
    $paragraphs = explode("\n\n", $plainText);


foreach ($paragraphs as $paragraph) {
    // Trim the paragraph to remove any leading/trailing whitespace
    $paragraph = trim($paragraph);

    // Skip empty paragraphs
    if (empty($paragraph)) {
        continue;
    }

    // Add each paragraph with indentation for the first line
    $section->addText($paragraph, null, [
        'indentation' => [
            'firstLine' => 920, // 0.5 inch indentation (720 twips)
            'name' => 'Times New Roman',
        ],
        'spacing' => [
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::JUSTIFY,
            'after' => 0, // No extra spacing after the paragraph
            'name' => 'Times New Roman',
        ],
    ]);

    // Add a line break after each paragraph
    $section->addTextBreak(1, null, ['spacing' => ['after' => 0]]);
}

    // Signature and QR code
    $signatureTable = $section->addTable();
    $signatureTable->addRow();
    $qrCell = $signatureTable->addCell(5000);
    $qrCell->addImage(storage_path('app/public/' . $letter->qr_code), [
        'width' => 50,
        'height' => 50,
    ]);

    $centerAlignStyle = [
        'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, // Center alignment
        'spaceAfter' => 0,
         'spaceBefore' => 0,
         'lineHeight' => 1,
         'name' => 'Times New Roman',
    ];

    // Add content to the signing authority cell with center alignment
    $signCell = $signatureTable->addCell(6000);
    foreach ($letter->signingAuthorities as $authority) {
        $signCell->addText($authority->name, ['bold' => true], $centerAlignStyle); // Name with bold and center alignment
        $signCell->addText($authority->designation, [], $centerAlignStyle); // Designation with center alignment
        $signCell->addText("For {$authority->department}", [], $centerAlignStyle); // Department with center alignment
        $signCell->addText($authority->other, [], $centerAlignStyle); // Phone number with center alignment
        $signCell->addTextBreak(); // Line break
    }
       // for date and letter no
        $headerTable = $section->addTable([
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
        ]);

        $headerTable->addRow();
        $cell1 = $headerTable->addCell(5000); // Adjust the width as needed
        $cell2 = $headerTable->addCell(6000, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        // Add letter_no in the left cell
        $headerStyle = [
            'bold' => false,
            'size' => 11,
        ];
        $cell1->addText(html2text($letter->letter_no), $headerStyle);

        // Add the date in the right cell
        $rightAlignStyle = [
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END, // Right alignment for date
            'bold' => true,
        ];
        $cell2->addText("Dated: the " . date('dS M, Y', strtotime($letter->date)), ['bold'=>true], $rightAlignStyle);

    // Forwarded copies
    $section->addText('A copy is forwarded for similar compliance:-', ['bold' => true]);
    $listStyle = [
        'listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER,
        'spaceAfter' => 0,
         'spaceBefore' => 0,
         'name' => 'Times New Roman',

    ];
    $listStyleType = [
        'spaceAfter' => 0,
         'spaceBefore' => 0,
         'name' => 'Times New Roman',

    ];
    foreach ($letter->forwardedCopies as $forward) {
        $section->addListItem($forward->copy_forwarded, 0, null, $listStyle,$listStyleType);
    }



   // Create a table for the signature and signing authorities
$signatureTable = $section->addTable();

// Add a row to the table
$signatureTable->addRow();

// Add the first cell for the QR code (or empty column if no image is needed)
$qrCell = $signatureTable->addCell(5000, ['valign' => 'center']); // Empty column (5000 width)

$signCell = $signatureTable->addCell(5000, ['valign' => 'center']); // Column for toLetter (5000 width)

// Center the text in the second cell
$centerAlignStyle = [
    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
    'spaceAfter' => 0,
    'spaceBefore' => 0,
    'lineHeight' => 1,
    'name' => 'Times New Roman',
];

// Add the signing authority details to the second column and center the content
foreach ($letter->designations as $toLetter) {
    $signCell->addText($toLetter->designation, ['bold' => true], $centerAlignStyle);
    $signCell->addText($toLetter->department, [], $centerAlignStyle);
    $signCell->addText($toLetter->address, [], $centerAlignStyle);
    if (!empty($toLetter->contact)) {
        $signCell->addText($toLetter->contact, [], $centerAlignStyle);
    }
    $signCell->addTextBreak(); // Add space between different designations
}



    // Save and download
    $letter_no = $letter->letter_no;
    if (strpos($letter_no, '/') !== false) {
        // Replace all slashes with underscores
        $letter_no = str_replace('/', '_', $letter_no);
    }
    $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
    $fileName = 'order_' . $letter_no. '.docx';
    $tempFile = tempnam(sys_get_temp_dir(), $fileName);
    $objWriter->save($tempFile);

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
        $letter_no = $letter->letter_no;
    if (strpos($letter_no, '/') !== false) {
        // Replace all slashes with underscores
        $letter_no = str_replace('/', '_', $letter_no);
    }
        $filename = 'letter_' . $letter_no . '.pdf';
        $path = 'signed_letters/';
        $filePath = storage_path('app/public/signed_letters/letter_' . $letter_no . '.pdf');
        $file->move(storage_path('app/public/'.$path ), $filename);
         // Calculate the hash of the uploaded file
        //  $uploadedFileHash = hash_file('sha256', $filePath);
        if ($file->getClientOriginalName() === $filename) {
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

public function letter_search(Request $request)
{
    $totalLetters = Letter::count();
        $users_form = Letter::withCount('user')->get();
        // $letters = Letter::with('user')->where('is_submitted',1)->orderBy('id', 'desc')->get();
        $draft = Letter::where('is_submitted',0)->get();
        $query = User::whereHas('roles', function ($q) {
            $q->where('name', Role::ROLE_ADMIN);
        })->with('roles')->select('users.*');
        $data['users'] = $query->count();
    $query_search = $request->input('query');
    $designation_search = $request->input('designation');
    $district_search = $request->input('district');

    $letters = Letter::with(['user', 'designations'])  // Eager load 'user' and 'designations'
    ->where('is_submitted', 1)
    ->where(function($query) use ($query_search, $designation_search, $district_search) {
        if ($query_search) {
            $query->where('letter_no', 'LIKE', "%{$query_search}%")
                ->orWhereHas('user', function($q) use ($query_search) {
                    $q->where('first_name', 'LIKE', "%{$query_search}%");
                });
        }
            if ($designation_search) {
                $query->whereHas('designations', function($q) use ($designation_search) {
                    $q->where('designation', 'LIKE', "%{$designation_search}%");
                });
            }
            if ($district_search) {
                $query->whereHas('user', function($q) use ($district_search) {
                    $q->where('district', 'LIKE', "%{$district_search}%");
                });
            }
        })
        ->get();

                    //  if ($request->ajax()) {
                    //     return response()->json([
                    //         'html' => view('super_admin.total_letters', compact('letters','data','users_form','totalLetters','draft'))->render() // Render only the table
                    //     ]);
                    // }
                    if ($request->ajax()) {

                        return view('super_admin.render_table_search', compact('letters'))->render();

                    }


    return view('super_admin.total_letters', compact('letters','data','users_form','totalLetters','draft'));
}
public function order_search(Request $request)
{
    $totalLetters = Order::count();
        $users_form = Order::withCount('user')->get();
        // $letters = Letter::with('user')->where('is_submitted',1)->orderBy('id', 'desc')->get();
        $draft = Order::where('is_submitted',0)->get();
        $query = User::whereHas('roles', function ($q) {
            $q->where('name', Role::ROLE_ADMIN);
        })->with('roles')->select('users.*');
        $data['users'] = $query->count();
    $query_search = $request->input('query');
    $designation_search = $request->input('designation');
    $district_search = $request->input('district');

    $letters = Order::with(['user', 'designations'])  // Eager load 'user' and 'designations'
    ->where('is_submitted', 1)
    ->where(function($query) use ($query_search, $designation_search, $district_search) {
        if ($query_search) {
            $query->where('letter_no', 'LIKE', "%{$query_search}%")
                ->orWhereHas('user', function($q) use ($query_search) {
                    $q->where('first_name', 'LIKE', "%{$query_search}%");
                });
        }
            if ($designation_search) {
                $query->whereHas('designations', function($q) use ($designation_search) {
                    $q->where('designation', 'LIKE', "%{$designation_search}%");
                });
            }
            if ($district_search) {
                $query->whereHas('user', function($q) use ($district_search) {
                    $q->where('district', 'LIKE', "%{$district_search}%");
                });
            }
        })
        ->get();

                    //  if ($request->ajax()) {
                    //     return response()->json([
                    //         'html' => view('super_admin.total_letters', compact('letters','data','users_form','totalLetters','draft'))->render() // Render only the table
                    //     ]);
                    // }
                    if ($request->ajax()) {

                        return view('super_admin.render_table_order_search', compact('letters'))->render();

                    }


    return view('super_admin.total_orders', compact('letters','data','users_form','totalLetters','draft'));
}

public function draft_search(Request $request)
{
    $totalLetters = Letter::count();
        $users_form = Letter::withCount('user')->get();
        // $letters = Letter::with('user')->where('is_submitted',1)->orderBy('id', 'desc')->get();
        $draft = Letter::where('is_submitted',0)->get();
        $query = User::whereHas('roles', function ($q) {
            $q->where('name', Role::ROLE_ADMIN);
        })->with('roles')->select('users.*');
        $data['users'] = $query->count();
    $query_search = $request->input('query');
    $designation_search = $request->input('designation');
    $district_search = $request->input('district');

    $letters = Letter::with(['user', 'designations'])  // Eager load 'user' and 'designations'
    ->where('is_submitted', 0)
    ->where(function($query) use ($query_search, $designation_search, $district_search) {
        if ($query_search) {
            $query->where('letter_no', 'LIKE', "%{$query_search}%")
                ->orWhereHas('user', function($q) use ($query_search) {
                    $q->where('first_name', 'LIKE', "%{$query_search}%");
                });
        }
        if ($designation_search) {
            $query->whereHas('designations', function($q) use ($designation_search) {
                $q->where('designation', 'LIKE', "%{$designation_search}%");
            });
        }
        if ($district_search) {
            $query->whereHas('user', function($q) use ($district_search) {
                $q->where('district', 'LIKE', "%{$district_search}%");
            });
        }
    })
    ->get();

                    //  if ($request->ajax()) {
                    //     return response()->json([
                    //         'html' => view('super_admin.total_letters', compact('letters','data','users_form','totalLetters','draft'))->render() // Render only the table
                    //     ]);
                    // }
                    if ($request->ajax()) {

                        return view('super_admin.render_draft_search', compact('letters'))->render();

                    }


    return view('super_admin.total_draft', compact('letters','data','users_form','totalLetters','draft'));
}

public function draft_search_order(Request $request)
{
    $totalLetters = Letter::count();
        $users_form = Letter::withCount('user')->get();
        // $letters = Letter::with('user')->where('is_submitted',1)->orderBy('id', 'desc')->get();
        $draft = Letter::where('is_submitted',0)->get();
        $query = User::whereHas('roles', function ($q) {
            $q->where('name', Role::ROLE_ADMIN);
        })->with('roles')->select('users.*');
        $data['users'] = $query->count();
    $query_search = $request->input('query');
    $designation_search = $request->input('designation');
    $district_search = $request->input('district');

    $letters = Order::with(['user', 'designations'])  // Eager load 'user' and 'designations'
    ->where('is_submitted', 0)
    ->where(function($query) use ($query_search, $designation_search, $district_search) {
        if ($query_search) {
            $query->where('letter_no', 'LIKE', "%{$query_search}%")
                ->orWhereHas('user', function($q) use ($query_search) {
                    $q->where('first_name', 'LIKE', "%{$query_search}%");
                });
        }
        if ($designation_search) {
            $query->whereHas('designations', function($q) use ($designation_search) {
                $q->where('designation', 'LIKE', "%{$designation_search}%");
            });
        }
        if ($district_search) {
            $query->whereHas('user', function($q) use ($district_search) {
                $q->where('district', 'LIKE', "%{$district_search}%");
            });
        }
    })
    ->get();

                    //  if ($request->ajax()) {
                    //     return response()->json([
                    //         'html' => view('super_admin.total_letters', compact('letters','data','users_form','totalLetters','draft'))->render() // Render only the table
                    //     ]);
                    // }
                    if ($request->ajax()) {

                        return view('super_admin.render_draft_search_order', compact('letters'))->render();

                    }


    return view('super_admin.total_draft_order', compact('letters','data','users_form','totalLetters','draft'));
}

    public function downloadSignedLetter(Letter $letter)
    {
// Check if the letter has a signed letter file
            if ($letter->signed_letter) {
                $filePath = storage_path('app/public/signed_letters/' . $letter->signed_letter);

                if (file_exists($filePath)) {
                    return response()->download($filePath);
                } else {
                    return redirect()->back()->with(['error' => 'File not found.']);
                }
            }

            return redirect()->back()->with(['error' => 'No signed letter available for download.']);
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
            public function order_preview($id){
                // Retrieve the letter by ID
                $letter = Order::findOrFail($id);

                $letter = $letter->load(['designations', 'signingAuthorities', 'forwardedCopies']);

                // Pass the letter data to a preview view
                return view('forms.order.preview', compact('letter'));
            }
}
