<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Imagick;
use Spatie\PdfToText\Pdf as PdfToText;
use Barryvdh\DomPDF\Facade\Pdf as DomPdf;


class AssignmentController extends Controller
{
    public function index()
    {
        $sessionExsits = session('sessionInProgress');
        if ($sessionExsits) {
            $rubicFilesNames = $this->getRubicFileNamesTemp();
        } else {
            $rubicFilesNames = $this->getRubicFileNames();
            $this->clearRubricTempDirectory();
        }
        return view('assignments-preview.index', compact('rubicFilesNames'));
    }
    public function getRubicFileNames()
    {
        $path = public_path('assets/rubrics');
        if (File::exists($path)) {
            $files = File::files($path);
            $fileNames = [];
            foreach ($files as $file) {
                $fileNames[] = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            }
            return $fileNames;
        } else {
            return [];
        }
    }
    public function getRubicFileNamesTemp()
    {
        $path = public_path('assets/rubrics/temp');
        if (File::exists($path)) {
            $files = File::files($path);
            $fileNames = [];
            foreach ($files as $file) {
                $fileNames[] = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            }
            $adminFiles = $this->getRubicFileNames();
            $fileNames = array_merge($adminFiles, $fileNames);
            return $fileNames;
        } else {
            return [];
        }
    }
    public function clearRubricTempDirectory()
    {
        $path = public_path('assets/rubrics/temp');

        // Ensure the directory exists
        if (file_exists($path) && is_dir($path)) {
            // Loop through files and delete them
            $files = glob($path . '/*'); // Get all file names in the directory
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file); // Delete the file
                }
            }
        }
    }
    public function uploadfile(Request $request)
    {
        $request->validate([
            'myfile' => 'required|mimes:pdf,jpeg,png,jpg,txt|max:10240', // Allow PDF, images, and text files, max size 10MB
        ]);

        $response = $this->handleFile($request);
        return $response;
    }

    public function handleFile($request)
    {
        $file = $request->file('myfile');
        $type = $request->filetype;
        $file = $request->file('myfile');
        $extension = $file->getClientOriginalExtension();
        $text = '';

        try {
            switch (strtolower($extension)) {
                case 'txt':
                    // Read text file contents
                    $text = file_get_contents($file->getPathname());
                    break;

                case 'pdf':
                    // Extract text from PDF using Spatie/PdfToText
                    $text = PdfToText::getText($file->getPathname());
                    break;

                case 'png':
                case 'jpg':
                case 'jpeg':
                    // Extract text from image using Imagick
                    $imagick = new Imagick();
                    $imagick->readImage($file->getPathname());
                    $imagick->setImageFormat('txt');
                    $text = $imagick->getImageBlob();
                    break;

                default:
                    throw new \Exception('Unsupported file type.');
            }

            return response()->json(['text' => $text]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    // public function handleFile($request)
    // {
    //     $file = $request->file('myfile');

    //     // Encode the file to Base64
    //     $base64Image = base64_encode(file_get_contents($file->getRealPath()));
    //     $mimeType = $file->getMimeType(); // Get MIME type of the uploaded file

    //     try {
    //         // Send the API request
    //         $response = Http::asForm()->withHeaders([
    //             'apikey' => env('OCR_SPACE_API_KEY'), // Get the API key from the environment file
    //         ])->post('https://api.ocr.space/parse/image', [
    //             'base64Image' => "data:$mimeType;base64,$base64Image",
    //             'language' => 'eng',
    //             'isOverlayRequired' => false,
    //         ]);

    //         if ($response->successful()) {
    //             // Extract the text from the response
    //             $result = $response->json();
    //             if (isset($result['ParsedResults'][0]['ParsedText'])) {
    //                 $text = $result['ParsedResults'][0]['ParsedText'];
    //                 return response()->json(['text' => $text]);
    //             } else {
    //                 return response()->json(['error' => 'Failed to extract text from the image.'], 400);
    //             }
    //         } else {
    //             return response()->json(['error' => 'Failed to process the file.'], 500);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }
    public function uploadRubicTemp(Request $request)
    {
        $request->validate([
            'csvFile' => 'required|file|mimes:csv|max:10240',
        ]);

        $file = $request->file('csvFile');
        $destinationPath = public_path('assets/rubrics/temp');
        $fileName = $file->getClientOriginalName();
        $filePath = $destinationPath . '/' . $fileName;

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true); // Create directory if it doesn't exist
        }
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        $file->move($destinationPath, $fileName);
        $filenames = $this->getRubicFileNamesTemp();
        Session::put('rubicsTemp', $filenames);
        Session::put('sessionInProgress', true);
        return response()->json(['message' => 'File uploaded successfully', 'filenames' => $filenames]);
    }
    public function uploadRubic(Request $request)
    {
        $request->validate([
            'csvFile' => 'required|file|mimes:csv|max:10240',
        ]);

        $file = $request->file('csvFile');
        $destinationPath = public_path('assets/rubrics');
        $fileName = $file->getClientOriginalName();
        $filePath = $destinationPath . '/' . $fileName;

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true); // Create directory if it doesn't exist
        }
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        $file->move($destinationPath, $fileName);
        $filenames = $this->getRubicFileNames();
        return response()->json(['message' => 'File uploaded successfully', 'filenames' => $filenames]);
    }

    public function startAssessment(Request $request)
    {
        // Extract input data
        $courseInformation = $request->input('courseName');
        $assignmentInstructions = $request->input('instructions');
        $studentEssay = $request->input('assignment');
        $rubicFileName = $request->input('rubicName');

        // Locate rubric file
        $path = public_path('assets/rubrics');
        $matchingFiles = glob($path . '/' . $rubicFileName . '.*');
        if (!empty($matchingFiles)) {
            $rubricInformation = file_get_contents($matchingFiles[0]); // Load rubric content
        } else {
            return response()->json(['error' => 'Rubric file not found'], 404);
        }

        // Define AI_ROLE and SYSTEM_INSTRUCTIONS
        $aiRole = "You are GradeAI, a replacement for teachers in a High School, located in North America.
You are a trained expert on writing and literary analysis. Your job is to accurately and effectively grade a student's essay and give them helpful feedback according to the assignment prompt.";
        $systemInstructions = 'Assess the students assignment based on the provided rubric.
Respond back with graded points and a level for each criteria. Dont rewrite the rubric in order to save processing power. In the end, write short feedback about what steps they might take to improve on their assignment. Write a total percentage grade and letter grade. In your overall response, try to be lenient and keep in mind that the student is still learning. While grading the essay remember the writing level the student is at while considering their course level, grade level, and the overall expectations of writing should be producing.
Your grade should only be below 70% if the essay does not succeed at all in any of the criteria. Your grade should only be below 80% if the essay is not sufficient in most of the criteria. Your grade should only be below 90% if there are a few criteria where the essay doesnt excell. Your grade should only be above 90% if the essay succeeds in most of the criteria.
Understand that the essay was written by a human and think about their writing expectations for their grade level/course level, be lenient and give the student the benefit of the doubt.

Additionally, provide a Reliability Index, which is a score from 0 to 100 indicating how confident you are in your assessment of the essay. A score of 0 means you have no confidence in your assessment, while a score of 100 means you are completely confident in your assessment.

Give me your entire response in JSON format for easy processing.
Response Format:
[
    {"Criteria": "...", "Level": "4", "Feedback": "Student must..."},
    {"Grade": "B", "Percentage": "85%"},
    {"Feedback": "Some suggestions to improve..."},
    {"ReliabilityIndex": 85}
]
where you create a Criteria object for each individual criteria, Grade represents the overall assignment grade, Feedback is a list of bullet points regarding the specific suggestions in their essay with references to examples in the essay, and ReliabilityIndex is your confidence score in the assessment.';

        // Construct the user prompt
        $userPrompt = <<<PROMPT
        System Instructions:
        $systemInstructions

        Course Information:
        $courseInformation

        Rubric:
        $rubricInformation

        Assignment Instructions:
        $assignmentInstructions

        Essay:
        $studentEssay
        PROMPT;

        try {
            // Make the API request
            $response = Http::timeout(300)->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'), // Add API key
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $aiRole,
                    ],
                    [
                        'role' => 'user',
                        'content' => $userPrompt,
                    ],
                ],
                'temperature' => 0.1,
                'top_p' => 0.3,
            ]);

            // Handle the API response
            if ($response->successful()) {
                $feedbackData = $response->json();
                return response()->json(['data' => $feedbackData]);
            } else {
                return response()->json(['error' => 'Failed to process the assessment.'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function exportPdf(Request $request)
    {
        $studentName = $request->studentName;
        $pdfContent = view('admin.template.template', compact('request'))->render();
        $pdf = DomPdf::loadHTML($pdfContent);
        $fileName = $studentName ? str_replace(' ', '_', $studentName) : 'exported_file';
        return $pdf->download($fileName . '.pdf');
    }
}
