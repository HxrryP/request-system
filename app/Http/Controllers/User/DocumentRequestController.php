<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DocumentRequest; // Import the DocumentRequest model
use App\Models\DocumentType;   // Import the DocumentType model
use Illuminate\Http\Request;      // Import the Request object
use Illuminate\Support\Facades\Auth; // Import the Auth facade for logged-in user

class DocumentRequestController extends Controller
{
    /**
     * Display a listing of the user's document requests.
     * Route: requests.index (GET /requests)
     */
    public function index()
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Fetch requests belonging to this user, load the related document type, order newest first, and paginate
        $requests = $user->documentRequests() // Assumes 'documentRequests' relationship exists in User model
                         ->with('documentType') // Eager load DocumentType details
                         ->latest() // Order by created_at descending
                         ->paginate(15); // Show 15 requests per page

        // Return the view, passing the requests data
        return view('user.requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new document request.
     * Route: requests.create (GET /requests/create)
     */
    public function create()
    {
        // Fetch active document types, order them, get all results, then group by category
        $documentTypes = DocumentType::where('is_active', true)
                                    ->orderBy('category')
                                    ->orderBy('name')
                                    ->get()
                                    ->groupBy('category'); // Group for better display in the view

        // Return the view, passing the grouped document types
        return view('user.requests.create', compact('documentTypes'));
    }

    /**
     * Store a newly created document request in storage.
     * Route: requests.store (POST /requests)
     */
    public function store(Request $request) // Inject the Request object to access form data
    {
        // --- Validation ---
        // Define basic validation rules
        $rules = [
            'document_type_id' => 'required|exists:document_types,id',
            // !!! ADD MORE SPECIFIC VALIDATION RULES HERE !!!
            // Based on what fields are needed for each document_type_id
            // Example: If document_type_id 1 is 'New Business Permit' needing 'business_name'
            // 'business_name' => 'required_if:document_type_id,1|string|max:255',
            // 'property_id' => 'required_if:document_type_id,5|string|max:100', // Example for RPT
            // 'person_name_on_cert' => 'required_if:document_type_id,10|string|max:255', // Example for LCR
        ];

        // Define custom messages if needed
        $messages = [
            'document_type_id.required' => 'Please select the document you want to request.',
            // 'business_name.required_if' => 'Please provide the business name for the permit.',
        ];

        // Validate the incoming request data
        $validatedData = $request->validate($rules, $messages);

        // --- Find Document Type Details ---
        $documentType = DocumentType::findOrFail($validatedData['document_type_id']);

        // --- Get Authenticated User ---
        $user = Auth::user(); // Or $request->user()
        // --- Create the Document Request ---
        try {
            $newRequest = $user->documentRequests()->create([
                'document_type_id' => $documentType->id,
                'status' => 'Pending Payment', // Initial status
                'details' => $request->except(['_token', 'document_type_id']), // Store other relevant form fields as JSON
                'payment_method' => null,
                'payment_reference' => null,
                'amount_paid' => null, // Will be updated after payment success
                'paid_at' => null,
                'admin_notes' => null,
            ]);

            // --- Redirect to Payment ---
            // Redirect the user to the payment page for the newly created request
            return redirect()->route('requests.pay', $newRequest->id)
                             ->with('success', 'Request created successfully! Please proceed with payment.');

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error creating document request: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()
                             ->with('error', 'There was an issue creating your request. Please try again.')
                             ->withInput(); // Keep old input in the form
        }
    }

    /**
     * Display the specified document request.
     * Route: requests.show (GET /requests/{documentRequest}) - Uses Route Model Binding
     */
    public function show(DocumentRequest $documentRequest) // Type-hinting automatically finds the request by ID
    {
        // --- Authorization ---
        // Ensure the logged-in user owns this request
        if (Auth::id() !== $documentRequest->user_id) {
            abort(403, 'Unauthorized action.'); // Or redirect with error
        }

        // Eager load relationships if needed and not already loaded by default
        $documentRequest->loadMissing('documentType', 'user');

        // Return the view, passing the specific request data
        return view('user.requests.show', compact('documentRequest'));
    }

    /**
     * Show the payment page for the specified document request.
     * Route: requests.pay (GET /requests/{documentRequest}/pay) - Uses Route Model Binding
     */
    public function pay(DocumentRequest $documentRequest)
    {
        // --- Authorization ---
        if (Auth::id() !== $documentRequest->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // --- Status Check ---
        // Ensure payment is actually needed
        if ($documentRequest->status !== 'Pending Payment') {
             // Maybe redirect back to the 'show' page with a message
            return redirect()->route('requests.show', $documentRequest->id)
                             ->with('info', 'This request does not require payment or is already being processed.');
        }

        // Eager load the document type to easily access the price in the view
        $documentRequest->loadMissing('documentType');

        // Return the payment view, passing the request data (which includes the price via documentType)
        return view('user.requests.pay', compact('documentRequest'));

        // --- NOTE on Payment Gateway Interaction ---
        // The actual *initiation* of payment (e.g., redirecting to GCash/Maya
        // or making an API call to get a payment link) might happen here,
        // OR more likely, it will happen via JavaScript/form submission
        // *from* the 'user.requests.pay' view based on which button the user clicks.
        // The code here just prepares and shows the page with payment options.
    }
}
