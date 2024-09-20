<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use File;
use Exception;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();
        return response()->json([
            'status' => 200,
            'users' => $user
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $userData = [
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'address' => $request->address,
    //         'phone' => $request->phone,
    //         'image'=> $request->image
    //     ];
    //     if ($request->hasFile('image')) {
    //         $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
    //         $request->file('image')->storeAs('images', $imageName, 'public');
    //         $userData['image'] = $imageName; // Save image name to validated data
    //     }
    //     User::create($userData);
    //     return response()->json([
    //         'status' => 200,
    //     ]);
    // }

    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $request->id,
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust max size as needed
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'phone' => $request->phone,
            'image' => $request->image,
        ];

        if ($request->hasFile('image')) {
            // Optionally handle deleting the old image if updating
            if ($request->id) {
                $user = User::find($request->id);
                if ($user && $user->image) {
                    Storage::disk('public')->delete('uploads/image/' . $user->image);
                }
            }

            // $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            // $request->file('image')->storeAs('images', $imageName, 'public');
            // $userData['image'] = $imageName; // Save image name to validated data

            $imageName = rand(111,999).time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/image'), $imageName);
            $userData['image']=$imageName;
        }

        User::updateOrCreate(['id' => $request->id], $userData);

        return response()->json([
            'status' => 200,
            'message' => 'User saved successfully.',
        ]);
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
        $user = User::find($id);
        if ($user) {
            return response()->json([
                'status' => 200,
                'employee' => $user
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Employee not found'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $user = User::find($id);
        // if ($user) {
        //     $user->name = $request->name;
        //     $user->email = $request->email;
        //     $user->address = $request->address;
        //     $user->phone = $request->phone;
        //     $user->save();
        //     return response()->json([
        //         'status' => 200,
        //         'message' => 'User updated successfully'
        //     ]);
        // } else {
        //     return response()->json([
        //         'status' => 404,
        //         'message' => 'Employee not found'
        //     ]);
        // }
         $user = User::find($id);

            if (!$user) {
                return response()->json(['status' => 404, 'message' => 'User not found']);
            }

            // Validate and update the user
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                // 'email' => 'required|string|email|max:255|unique:users,email,' . $id,
                'email' => 'required|string|email|max:255|unique:users,email,' . $id,
                'address' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);
            if ($request->hasFile('image')) {
                // Delete old image if necessary
                $image_path=public_path('uploads/image/').$user->image;
                if ($user->image) {
                    if(File::exists($image_path)) 
                        File::delete($image_path);
            
                    // Storage::disk('public')->delete('uploads/image/' . $user->image);
                }
                
                // $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
                // $request->file('image')->storeAs('images', $imageName, 'public');
                // $validatedData['image'] = $imageName; // Save new image name to validated data
                
                $imageName = rand(111,999).time().'.'.$request->image->extension();
                $request->image->move(public_path('uploads/image'), $imageName);
                $validatedData['image']=$imageName;
            }

            $user->update($validatedData);

            return response()->json(['status' => 200, 'message' => 'User updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if ($user->delete()) {
            return response()->json(['status' => 200, 'message' => 'User deleted successfully.']);
        } else {
            return response()->json(['status' => 400, 'message' => 'Failed to delete employee.']);
        }
    }
}
