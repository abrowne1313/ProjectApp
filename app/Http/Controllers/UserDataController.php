<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserDataController extends Controller
{
    /**
     * Show login form.
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Handle login submission.
     */
    public function login(Request $request)
    {
        // Validate login input
        $request->validate([
            'UserEmail' => 'required|email',
            'password' => 'required'
        ]);

        // Attempt to log in
if (Auth::attempt(['UserEmail' => $request->UserEmail, 
                    'password' => $request->password])) {
    return redirect('/dashboard');
}

        // If login fails
        return back()->withErrors([
            'UserEmail' => 'Invalid UserEmail or password.',
        ]);

        dd([
    'input' => $request->only('UserEmail', 'PasswordHash'),
    'auth_attempt' => Auth::attempt([
        'UserEmail' => $request->UserEmail,
        'password' => $request->PasswordHash
    ]),
    'hashed_password_in_db' => \App\Models\UserData::where('UserEmail', $request->UserEmail)->value('PasswordHash'),
]);
    }


/**
 * Function to show classes assigned to each teacher on opening of dashboard
 */
public function index()
{
    $user = Auth::user();

    $classes = $user->classes; 
    $firstName = optional($user->userData)->FirstName;

    return view('dashboard', compact('classes', 'firstName'));
}
    /**
     * Admin controls (for /admin route).
     */
    public function AdminControls()
    {
        $users = UserData::all();
        return view('admincontrols/admin', compact('users'));
    }

    /**
     * Display form to create a new user (/createuser).
     */
    public function CreateUserForm()
    {
        return view('admincontrols/createuser');
    }


    /**
     * Store a newly created user. (POST /userdata)
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'FirstName'     => 'required|string|max:255',
            'Surname'     => 'required|string|max:255',
            'user_type' => 'required|integer|max:11',
            'UserEmail' => 'required|email|unique:user_data,UserEmail',
            'password' => 'required|min:6|max:30'
        ]);

        // Create user
        UserData::create([
            'FirstName'     => $request->FirstName,
            'Surname'     => $request->Surname,
            'user_type' => $request->user_type,
            'UserEmail'    => $request->UserEmail,
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('CreateUser')->with('success', 'UserData created successfully.');
    }


    // Search for an existing user to update
public function liveSearch(Request $request)
{
    $q = $request->get('q');

    if (!$q) {
        return response()->json([]);
    }

    $users = UserData::where('FirstName', 'like', "%{$q}%")
        ->orWhere('Surname', 'like', "%{$q}%")
        ->orWhere('UserEmail', 'like', "%{$q}%")
        ->limit(10)
        ->get([
            'id',
            'FirstName',
            'Surname',
            'UserEmail'
        ]);

    return response()->json($users);
}

public function GetEditUserPage()
{
    return view('admincontrols/edituserdata');
}

    /**
     * Show form to edit a user. (GET /userdata/{id}/edit)
     */
public function edit(UserData $user)
{
    return view('admincontrols/edituserdata', compact('user'));
}

    /**
     * Update user data. (PUT /userdata/{id})
     */
  public function update(Request $request, UserData $user)
{
    $validated = $request->validate([
        'FirstName'  => 'required|string|max:255',
        'Surname'    => 'required|string|max:255',
        'UserEmail'  => 'required|email|unique:user_data,UserEmail,' . $user->id,
        'user_type'  => 'required|integer|in:1,2,3,4',
        ]);

    // Update basic fields
    $user->FirstName = $validated['FirstName'];
    $user->Surname   = $validated['Surname'];
    $user->UserEmail = $validated['UserEmail'];
    $user->user_type = $validated['user_type'];
    $user->save();



    return redirect()
        ->route('EditUser')
        ->with('success', 'User updated successfully.');
}


    /**
     * Delete a user. (DELETE /userdata/{id})
     */
    public function destroy($id)
    {
        UserData::findOrFail($id)->delete();

        return redirect()->route('userdata.index')->with('success', 'UserData deleted successfully.');
    }


    public function ChangeUserPasswordForm()
    {
        return view('admincontrols/ChangeUserPassword');
    }

 public function ChangeUserPassword(Request $request)
  { 
    // Validate input
     $request->validate([ 
        'UserEmail' => 'required|email', 
        'newpassword1' => 'required|min:6', 
        'newpassword2' => 'required|same:newpassword1' 
    ]); 
    // Find the user 
    $user = UserData::where('UserEmail', $request->UserEmail)->first(); 
    if (!$user) {
         return back()->withErrors(['UserEmail' => 'User not found.']); 
        } 
        // Update password 
        $user->password = Hash::make($request->newpassword1); 
        $user->save(); 
        
        return redirect()->back()->with('success', 'Password updated successfully!'); 
    }



} 