<?php

namespace App\Http\Controllers;

use App\Models\PrinterCommand;
use Illuminate\Http\Request;

class PrinterCommandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $printerCommands = PrinterCommand::all();
        return response()->json($printerCommands);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'data' => 'required',
            'printer_type' => 'required',
            'printer_name' => 'nullable',
            'branch_id' => 'required',
            'user_id' => 'required',
        ]);

        $printerCommand = PrinterCommand::create($validatedData);
        return response()->json($printerCommand, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $printerCommand = PrinterCommand::find($id);
        if (!$printerCommand) {
            return response()->json(['message' => 'Printer command not found'], 404);
        }
        return response()->json($printerCommand);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'data' => 'required',
            'printer_type' => 'required',
            'printer_name' => 'required',
            'branch_id' => 'required',
            'user_id' => 'required',
        ]);

        $printerCommand = PrinterCommand::find($id);
        if (!$printerCommand) {
            return response()->json(['message' => 'Printer command not found'], 404);
        }

        $printerCommand->update($validatedData);
        return response()->json($printerCommand, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $printerCommand = PrinterCommand::find($id);
        if (!$printerCommand) {
            return response()->json(['message' => 'Printer command not found'], 404);
        }

        $printerCommand->delete();
        return response()->json(['message' => 'Printer command deleted'], 204);
    }

    public function getMyPrinterCommands(Request $request)
    {

        $user_id = $request->input('user_id');
        $branch_id = $request->input('branch_id');

        $printerCommands = PrinterCommand::where('user_id', $user_id)->get();

        // Delete the fetched printer commands
        foreach ($printerCommands as $command) {
            $command->delete();
        }

        return response()->json($printerCommands);
    }

    public function printerCommandsCount(Request $request)
    {
        $validatedData = $request->validate([
            'data' => 'required',
            'printer_type' => 'required',
            'printer_name' => 'nullable',
            'branch_id' => 'required',
            'user_id' => 'required',
        ]);

        $count = $request->input('count');

        $printerCommands = [];
        for ($i = 0; $i < $count; $i++) {
            $printerCommand = PrinterCommand::create($validatedData);
            $printerCommands[] = $printerCommand;
        }

        return response()->json($printerCommands, 201);
    }
}
