<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;
use URL;
use App\Models\Note;

class NoteController extends Controller
{
    /**
     * centralContect 
     * @return json response
     */
    public function notes(Request $request, $id = null)
    {
        try {
          switch ($request->method()) {
                case 'GET':
                    if ($id) {
                        // Logic for showing a single note (GET /notes/{id})
                        $_data = Note::find($id);
                        $notesArray = [];
                        $i = 0;
                        foreach ($_data as $value) {
                            $contact = json_decode($value->phone);
                            $notesArray[$i] = [
                                "id" => $value->id,
                                "title" => $value->title,
                                "descriptions" => $value->descriptions,
                                "status" => $value->status
                            ];
                            $i++;
                        }

                        if (count($notesArray) != 0) {
                            return response()->json(
                                [
                                    "statusCode" => 200,
                                    "status" => "success",
                                    "data" => $notesArray,
                                ],
                                200
                            );
                        } else {
                            return response()->json(
                                [
                                    "statusCode" => 200,
                                    "status" => "success",
                                    "data" => "No data found kk",
                                ],
                                200
                            );
                        }
                    } else {
                        // Logic for listing all notes (GET /notes)
                        $_data = Note::all();
                        $notesArray = [];
                        $i = 0;
                        foreach ($_data as $value) {
                            $contact = json_decode($value->phone);
                            $notesArray[$i] = [
                                "id" => $value->id,
                                "title" => $value->title,
                                "descriptions" => $value->descriptions,
                                "status" => $value->status
                            ];
                            $i++;
                        }

                        if (count($notesArray) != 0) {
                            return response()->json(
                                [
                                    "statusCode" => 200,
                                    "status" => "success",
                                    "data" => $notesArray,
                                ],
                                200
                            );
                        } else {
                            return response()->json(
                                [
                                    "statusCode" => 200,
                                    "status" => "success",
                                    "data" => "No data found without id",
                                ],
                                200
                            );
                        }
                    }
                    break;
                case 'POST':
                    // Logic for creating a new note (POST /notes)
                    $request->validate([
                        'title' => 'required|max:255',
                        'descriptions' => 'required',
                    ]);
                    try {
                        $contact = Note::create($request->all());
                        return response()->json(
                            [
                                "statusCode" => 200,
                                "status" => "success",
                                "data" => "Note added successfully",
                            ],
                            200
                        );
                    } catch (ModelNotFoundException $ex) {
                        return response()->json(
                            [
                                "statusCode" => 502,
                                "status" => "fail",
                                "errorMessage" => $ex,
                            ],
                            502
                        );
                    }
                    break;
                case 'PUT':
                    // Logic for updating an existing note (PUT /notes/{id})
                    //print('33333');
                    //die($id);
                    $request->validate([
                        'title' => 'required|max:255',
                        'descriptions' => 'required',
                    ]);
                    try {
                        $post = Note::find($id);
                        $post->update($request->all());
                        return response()->json(
                            [
                                "statusCode" => 200,
                                "status" => "success",
                                "data" => "Note Updated successfully",
                            ],
                            200
                        );
                    } catch (ModelNotFoundException $ex) {
                        return response()->json(
                            [
                                "statusCode" => 502,
                                "status" => "fail",
                                "errorMessage" => $ex,
                            ],
                            502
                        );
                    }
                    break;
                case 'DELETE':
                    // Logic for deleting a note (DELETE /notes/{id})
                     if ($id) {
                        try {
                            $delete = Note::find($id);
                            $result = $delete->delete();
                            return response()->json(
                                [
                                    "statusCode" => 200,
                                    "status" => "success",
                                    "data" => "Note Deleted Successfully",
                                ],
                                200
                            );
                        } catch (ModelNotFoundException $ex) {
                            Alert::error($ex);
                            return response()->json(
                                ["statusCode" => 502, "status" => "fail"],
                                502
                            );
                        }
                    } else {
                        return response()->json(
                            [
                                "statusCode" => 200,
                                "status" => "success",
                                "data" => "Note Not Exist",
                            ],
                            200
                        );
                    }
                    break;
                default:
                    // Handle other methods or errors
                    return response()->json(
                        [
                            "statusCode" => 502,
                            "status" => "fail",
                        ],
                        502
                    );
                    break;
            }
            
        } catch (ModelNotFoundException $ex) {
            return response()->json(
                [
                    "statusCode" => 502,
                    "status" => "fail",
                    "errorMessage" => $ex,
                ],
                502
            );
        }
    }
}
