<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Letter;
use App\Models\Order;
use App\Models\OrderToLetter;
use App\Models\OrderSigningAuthority;
use App\Models\OrderForwardCopy;
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
use PhpOffice\PhpWord\Settings;
use PHPHtmlToDoc\PHPHtmlToDoc;
use HTMLPurifier;
use HTMLPurifier_Config;
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Cell;
use PhpOffice\PhpWord\Style\Font;


class OrderController extends Controller
{
    public function order_create(Order $letter, User $user){
        $user = auth()->user();

    // $lastLetter = Letter::latest('id')->first();

    $prefix = $user->letter_no; // Assuming this field exists in the user table

    // Fetch the latest letter for this user based on their prefix
    $lastLetter = Order::where('letter_no', 'LIKE', "{$prefix}%")
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
        return response()->view('forms.order.create',compact('newLetterNo'));

    }
    public function order_store(Request $request){
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
        'subject' => 'nullable',
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
    $lastLetter = Order::where('letter_no', 'LIKE', "{$prefix}%")
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
        $letter = new Order();
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
                $design = new OrderToLetter();
                $design->order_id = $letter->id;
                $design->designation = $designation['designation'];
                $design->department = $designation['department'];
                $design->address = $designation['address'];
                $design->contact = $designation['contact'];
                $design->save();
            }
        //

        //  // Save signing authorities
         foreach ($request->input('signing_authorities', []) as $authority) {
            $sa = new OrderSigningAuthority();
            $sa->order_id = $letter->id;
            $sa->name = $authority['sa_name'];
            $sa->designation = $authority['sa_designation'];
            $sa->department = $authority['sa_department'];
            $sa->other = $authority['sa_other'];
            $sa->save();
        }
           // Save forwarded copies
        foreach ($request->input('forwarded_copies', []) as $copy) {
            $fc = new OrderForwardCopy();
            $fc->order_id = $letter->id;
            $fc->copy_forwarded = $copy['copy_forwarded'];
            $fc->save();
        }
        DB::commit();
        $message = $isSubmitted ? 'Order submitted successfully!' : 'Order saved as draft.';
        return redirect()->route('forms.order.edit',$letter->id)->with('message', $message);

    }
    public function order_edit(Order $letter){

        if ($letter->is_submitted==1) {
            return redirect()->route('admin.dashboard')->with('error', 'Submitted Orders cannot be edited.');
        }
        // $letter = Letter::with('designations');
        return view('forms.order.edit', compact('letter'));
    }
    public function generateQRCode(Order $letter)
    {

           // Generate the route for the signed letter
           $letter->save();
           $route = route('orders.view', $letter->id);
           $qrCodePath = 'qr-codes/order_' . $letter->id . '.png';

           // Generate the QR code with the generated route
           QrCode::format('png')
               ->size(200)
               ->generate($route, storage_path('app/public/' . $qrCodePath));

           // Save the QR code path and link in the database
           $letter->qr_code = $qrCodePath;
           $letter->qr_code_link = $route;
           $letter->save();
    }

    public function view(Order $letter)
    {
        $filePath = storage_path('app/public/signed_letters/order_' . $letter->letter_no . '.pdf');

    if ($letter->is_submitted == 1 && file_exists($filePath)) {
        // If the letter is submitted and the signed letter file exists, download it directly
        return response()->download($filePath);
    } else {
        // If the letter is not submitted or doesn't have a signed version, download the original letter
        $originalLetterPath = storage_path('app/public/downloaded_letters/order_' . $letter->letter_no . '.pdf');
        if (file_exists($originalLetterPath)) {
            return response()->download($originalLetterPath);
        } else {
            return redirect()->route('Form.download.pdf',$letter->id);
        }
    }
    }


    public function downloadPdf(Order $letter)
    {
        // Load letter data with related models for designations, signing authorities, and forwarded copies
        $letter = $letter->load(['designations', 'signingAuthorities', 'forwardedCopies']);

        // Generate PDF from a view
        $pdf = PDF::loadView('forms.order.pdf', compact('letter'));
        $letter_no = $letter->letter_no;
        if (strpos($letter_no, '/') !== false) {
            // Replace all slashes with underscores
            $letter_no = str_replace('/', '_', $letter_no);
        }
          $fileName = 'order_' . $letter_no . '.pdf';
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

    public function order_update(Request $request, Order $letter)
    {
        if ($letter->is_submitted==1) {
            return redirect()->route('admin.dashboard')->with('error', 'Submitted Orders cannot be updated.');
        }

        DB::beginTransaction();

        try {
            $request->validate([
                // 'letter_no' => 'required',
                // 'head_title' => 'required',
                // 'fix_address' => 'required',
                // 'date' => 'required',
                'designation' => 'required',
                // 'subject' => 'required',
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
            $toLetter = OrderToLetter::find($designation['id']);
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
            $message = $isSubmitted ? 'Order submitted & Updated successfully!' : 'Order saved as draft & Updated.';
            return redirect()->back()->with('message', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating letter: ' . $e->getMessage());
        }
    }

    public function preview($id){
        // Retrieve the letter by ID
        $letter = Order::findOrFail($id);

        $letter = $letter->load(['designations', 'signingAuthorities', 'forwardedCopies']);

        // Pass the letter data to a preview view
        return view('forms.order.preview', compact('letter'));
    }

    public function uploadSignedOrder(Request $request, Order $letter)
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
        $filename = 'order_' . $letter_no . '.pdf';
        $path = 'signed_letters/';
        $filePath = storage_path('app/public/signed_letters/order_' . $letter_no . '.pdf');
        $file->move(storage_path('app/public/'.$path ), $filename);
         // Calculate the hash of the uploaded file
        //  $uploadedFileHash = hash_file('sha256', $filePath);
        if ($file->getClientOriginalName() === $filename) {
        $letter->signed_letter = $filename; // Store only the filename
        $letter->is_submitted = 1;
        $uploadedLetterPath = route('orders.download_signed', $letter->id); // Adjust this route as necessary
        $letter->qr_code_link = $uploadedLetterPath;
        $letter->save();
        return redirect()->route('admin.dashboard')->with(['message' => 'Signed order uploaded & submitted successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Uploaded order does not match the original order'], 400);
        }
    }

    return redirect()->back()->with(['error' => 'No file uploaded'], 400);
}
public function downloadSignedOrder(Order $letter)
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

        public function total_order(Order $letter){
            $user = Auth::user()->id;
            $users_form = Letter::where(['user_id'=>$user,'is_submitted'=>1])->get();
            $users_order = Order::where(['user_id'=>$user,'is_submitted'=>1])->get();
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
            $letters = Order::where(['user_id'=>$user,'is_submitted'=>1])->orderBy('id', 'desc')->get();
            return view('forms.order.single-order',compact('letters','total_letters','total_drafts','users_order'));
        }

        public function total_draft_order(Order $letter){
            $user = Auth::user()->id;
            $users_form = Letter::where(['user_id'=>$user,'is_submitted'=>1])->get();
            $draft = Letter::where(['user_id'=>$user,'is_submitted'=>0])->get();
            $draft_order = Order::where(['user_id'=>$user,'is_submitted'=>0])->get();
            $users_order = Order::where(['user_id'=>$user,'is_submitted'=>1])->get();

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
            $letters = Order::where(['user_id'=>$user,'is_submitted'=>0])->orderBy('id', 'desc')->get();
            return view('forms.order.draft-order',compact('letters','total_letters','total_drafts','users_order','draft_order'));
        }

}
