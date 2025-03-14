<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Resources\SingleUserResource;
use App\Models\Position;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use App\Models\Token;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request) {
        try {
            $validated = $request->validate([
                'page' => ['nullable', 'integer', 'min:1'],
                'count' => ['nullable', 'integer', 'min:1', 'max:100'],
            ]);

            $count = $validated['count'] ?? 5;
            $users = User::with('position')->paginate($count);

            $nextUrl = $users->nextPageUrl() ? $users->nextPageUrl() . '&count=' . $count : null;
            $prevUrl = $users->previousPageUrl() ? $users->previousPageUrl() . '&count=' . $count : null;

            return response()->json([
                'success' => true,
                'page' => $users->currentPage(),
                'total_pages' => $users->lastPage(),
                'total_users' => $users->total(),
                'count' => $users->perPage(),
                'links' => [
                    'next_url' => $nextUrl,
                    'prev_url' => $prevUrl,
                ],
                'users' => UserResource::collection($users),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Users not found',
            ], Response::HTTP_NOT_FOUND);
        } catch (ValidationException $e) {
            if ($e->status === 422) {
                return response()->json([
                    'success' => false,
                    'message' => 'Users not found',
                ], 422);
            }

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred',
            ], 500);
        }
    }

    public function show($id) {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => ['required', 'integer', 'min:1'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                ], 400);
            }

            $user = User::with('position')->where('id', $id)->get();

            if (!count($user)) {
                throw new ModelNotFoundException( 404);
            }

            return response()->json([
                'success' => true,
                'user' => SingleUserResource::collection($user),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
            ], 500);
        }
    }

    public function store(Request $request) {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|min:2|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|string|max:20|unique:users,phone',
                'position' => 'required|integer|min:1',
                'photo' => 'required|image|mimes:jpeg,jpg|max:5120|dimensions:min_width=70,min_height=70',
            ]);

            $token = Token::first();

            if ($token && $token->is_used === 0 && $token->updated_at->diffInMinutes(Carbon::now()) <= 40) {
            // if ($token) {
                $user = User::create([
                    'name' => $validatedData['name'],
                    'email' => $validatedData['email'],
                    'phone' => $validatedData['phone'],
                    'position_id' => $validatedData['position'],
                    'registration_timestamp' => Carbon::now()->timestamp,
                    'photo' => $this->resizeImage($request->file('photo')),
                ]);

                $token->is_used = 1;
                $token->save();

                return response()->json([
                    'success' => true,
                    'user_id' => $user->id,
                    'message' => 'New user successfully registered',
                ], 201);
            } else if($token && $token->updated_at->diffInMinutes(Carbon::now()) > 40) {
                return response()->json([
                    'success' => false,
                    'message' => 'The token expired.',
                ], 401);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'The token is already used.',
                ], 401);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'fails' => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062) {
                return response()->json([
                    'success' => false,
                    'message' => 'User with this phone or email already exist',
                ], 409);
            }
        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'fails' => $e->errorInfo,
            ], 500);
        }
    }

    private function resizeImage($photo) {
        $apiKey = env('TINIFY_API_KEY');
        $handle = fopen($photo, "rb");
        $contents = fread($handle, filesize($photo));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.tinify.com/shrink');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_USERPWD, "api:". $apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: image/jpeg',]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $contents);

        $response = curl_exec($ch);

        if(curl_errno($ch)) {
            return response()->json(['error' => curl_error($ch)], 500);
        }

        curl_close($ch);
        fclose($handle);

        $responseData = json_decode($response, true);

        if (isset($responseData['output']['url'])) {
            $url = $responseData['output']['url'];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_USERPWD, "api:". $apiKey);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            $data = [
                'resize' => [
                    'method' => 'cover',
                    'width' => 75,
                    'height' => 75
                ],
            ];
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $response = curl_exec($ch);
            curl_close($ch);

            $photoFullPath = 'photos/'. Str::random(30).Carbon::now()->timestamp. '.jpg';
            Storage::disk('public')->put($photoFullPath, $response);

            return $photoFullPath;

        } else {
            return response()->json(['error' => 'Failed to compress or resize image'], 500);
        }
    }

}
