<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\toDoListModel;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\toDoListResource;
use Illuminate\Support\Facades\Validator;

class toDoListController extends Controller
{
    public function index()
    {

        try {

            $toDoList = toDoListModel::orderBy('priority')->latest()->paginate(5);

            $toDoList->getCollection()->each(function ($item) {

                $item->append('status');

                $item->update(['status' => $item->status]);
            });

            if (count($toDoList) === 0) {

                return response()->json(new toDoListResource(false, 'Data tugas masih kosong', null), Response::HTTP_NOT_FOUND);
            }

            return new toDoListResource(true, 'List Data Tugas', $toDoList);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json(new toDoListResource(false, 'Internal Server Error', null), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [

                'title'         => 'required|regex:/^[A-Z\s]*$/',
                'description'   => 'required',
                'priority'      => 'required|numeric|in:1,2,3',
                'due_date'      => 'required'
            ]);

            if ($validator->fails()) {

                return response()->json($validator->errors(), 422);
            }

            $toDoList = toDoListModel::create([
                'title'         => $request->title,
                'description'   => $request->description,
                'priority'      => $request->priority,
                'due_date'      => $request->due_date,
                'completed'     => false,
                'status'        => 'Belum Selesai'
            ]);

            return new toDoListResource(true, 'Tugas Berhasil Ditambahkan', $toDoList);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json(new toDoListResource(false, 'Internal Server Error', null), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {

        try {
            $toDoList = toDoListModel::find($id);

            if ($toDoList === null) {

                return response()->json(new toDoListResource(false, 'Data tidak ditemukan', null), Response::HTTP_NOT_FOUND);
            }

            return new toDoListResource(true, 'Detail Data Post!', $toDoList);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json(new toDoListResource(false, 'Internal Server Error', null), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function filterTask(Request $request, $column, $value)
    {

        try {
            $toDoList = toDoListModel::where($column, $value)->get();

            if (count($toDoList) === 0) {

                return response()->json(new toDoListResource(false, 'Filter tidak menghasilkan data', null), Response::HTTP_NOT_FOUND);
            }

            return new toDoListResource(true, 'Data Berdasarkan Filter', $toDoList);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json(new toDoListResource(false, 'Internal Server Error', null), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id)
    {

        try {

            $validator = Validator::make($request->all(), [

                'title'         => 'required|regex:/^[A-Z\s]*$/',
                'description'   => 'required',
                'priority'      => 'required|numeric|in:1,2,3',
                'completed'     => 'required|boolean',
                'due_date'      => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $toDoList = toDoListModel::find($id);

            if ($toDoList === null) {

                return response()->json(new toDoListResource(false, 'Data tidak ditemukan', null), Response::HTTP_NOT_FOUND);
            }

            $toDoList->update([
                'title'         => $request->title,
                'description'   => $request->description,
                'priority'      => $request->priority,
                'due_date'      => $request->due_date,
                'completed'     => $request->completed,
            ]);

            return new toDoListResource(true, 'Data Task Berhasil Diubah!', $toDoList);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json(new toDoListResource(false, 'Internal Server Error', null), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        try {

            $toDoList = toDoListModel::find($id);

            if ($toDoList === null) {

                return response()->json(new toDoListResource(false, 'Data tidak ditemukan', null), Response::HTTP_NOT_FOUND);
            }

            $toDoList->delete();

            return new toDoListResource(true, 'Data Task Berhasil Dihapus!', null);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json(new toDoListResource(false, 'Internal Server Error', null), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
