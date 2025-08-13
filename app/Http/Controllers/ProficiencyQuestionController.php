<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\ProficiencyQuestion;
use Illuminate\Support\Facades\File;

class ProficiencyQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('ProficiencyQuestion');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('ProficiencyQuestion');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'aiken_file' => 'required|file|mimes:txt|max:2048',
        ]);

        try {
            // Get the file content
            $file = $request->file('aiken_file');
            $content = File::get($file);

            // Parse the content into an array of question data
            $questionsData = $this->parseAikenContent($content);

            // Save each question to the database
            foreach ($questionsData as $questionData) {
                ProficiencyQuestion::create($questionData);
            }

            return redirect()->back()->with('success', 'Aiken file uploaded and questions saved successfully!');
        } catch (\Exception $e) {
            // Handle any parsing or saving errors
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    private function parseAikenContent(string $content): array
    {
        $questions = [];
        $lines = preg_split("/\r\n|\n|\r/", $content);
        $currentQuestion = [];
        $isFirstLine = true;

        foreach ($lines as $line) {
            $line = trim($line);

            // If the line is empty, it's a separator between questions
            if (empty($line) && !empty($currentQuestion)) {
                $questions[] = $this->processQuestionBlock($currentQuestion);
                $currentQuestion = [];
                $isFirstLine = true;
                continue;
            }

            if (!empty($line)) {
                $currentQuestion[] = $line;
            }
        }

        // Process the last question if there's no trailing empty line
        if (!empty($currentQuestion)) {
            $questions[] = $this->processQuestionBlock($currentQuestion);
        }

        return $questions;
    }

    /**
     * Processes a single question block and extracts its data.
     */
    private function processQuestionBlock(array $block): array
    {
        $questionData = [
            'question' => $block[0] ?? '',
            'options' => null,
            'answer_key' => null,
            'points' => 0,
            'type' => 'multiple_choice',
        ];

        $options = [];
        $points = 0;
        $answerLine = null;
        $typeLine = null;

        foreach ($block as $line) {
            if (preg_match('/^[A-D]\.\s/', $line)) {
                $options[] = substr($line, 3);
            } elseif (preg_match('/^ANSWER:\s*(.+)/', $line, $matches)) {
                $answerLine = $matches[1];
            } elseif (preg_match('/^TYPE:\s*(.+)/', $line, $matches)) {
                $typeLine = $matches[1];
            } elseif (preg_match('/^POINTS:\s*(\d+)/', $line, $matches)) {
                $points = (int) $matches[1];
            }
        }

        if ($typeLine) {
            $questionData['type'] = strtolower($typeLine);
        } elseif ($answerLine && count($options) > 0) {
            if (str_contains($answerLine, ',')) {
                $questionData['type'] = 'checkbox';
            } else {
                $questionData['type'] = 'multiple_choice';
            }
        } elseif ($answerLine) {
            $questionData['type'] = 'fill';
        }

        switch ($questionData['type']) {
            case 'multiple_choice':
                $questionData['options'] = $options;
                $questionData['answer_key'] = $answerLine;
                break;
            case 'checkbox':
                $questionData['options'] = $options;
                $questionData['answer_key'] = $answerLine;
                break;
            case 'fill':
            case 'true_false':

                $questionData['answer_key'] = $answerLine;
                $questionData['question'] = $block[0];
                break;
            case 'essay':

                break;
        }

        $questionData['points'] = $points;
        return $questionData;
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
