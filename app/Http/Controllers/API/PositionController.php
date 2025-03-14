<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Position;
use App\Http\Resources\PositionResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;

class PositionController extends Controller
{
    public function index() {
        try {
            $positions = Position::all();
            return response()->json([
                'success' => true,
                'positions' => PositionResource::collection($positions),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Positions not found',
            ], Response::HTTP_NOT_FOUND);
        } catch (ValidationException $e) {
            if ($e->status === 422) {
                return response()->json([
                    'success' => false,
                    'message' => 'Positions not found',
                ], 422);
            }

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred',
            ], 500);
        }
    }
}
