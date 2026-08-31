<?php

/*
|--------------------------------------------------------------------------
| Developed by: Moniruzzaman Monir
| Email: monir112761@gmail.com
| Website: https://rcit-solution.com
|--------------------------------------------------------------------------
*/
namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\UserAnswer;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index(Request $request)
    {
        $totalResults = Result::count();
        $query = Result::with(['user', 'exam']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($user) use ($search) {
                    $user->where('name', 'like', "%{$search}%");
                })
                    ->orWhereHas('exam', function ($exam) use ($search) {
                        $exam->where('title', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $results = $query->latest()->paginate(10);
        $filterResults = ($request->filled('search') || $request->filled('date')) ? $results->total() : 0;

        return view('admin.result.index', compact('results', 'totalResults', 'filterResults'));
    }

    public function view($id)
    {
        $result = Result::with(['user', 'exam'])->findOrFail($id);
        $answers = UserAnswer::with('question')->where('result_id', $id)->get();

        return view('admin.result.view', compact('result', 'answers'));
    }

    public function userResults()
    {
        $results = Result::with('exam')
            ->where('user_id', session('user_id'))
            ->latest()
            ->paginate(10);

        return view('user.results.index', compact('results'));
    }

    public function userResultView($id)
    {
        $result = Result::with('exam')
            ->where('user_id', session('user_id'))
            ->findOrFail($id);

        $answers = UserAnswer::with('question')->where('result_id', $id)->get();

        return view('user.results.view', compact('result', 'answers'));
    }

    public function apiIndex()
    {
        return response()->json(Result::with(['user', 'exam'])->latest()->paginate(15));
    }

    public function apiShow($id)
    {
        $result = Result::with(['user', 'exam'])->findOrFail($id);

        return response()->json($result);
    }
}
