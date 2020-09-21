<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\UserValidator;
use App\Http\Requests\UserLoginRequest;
use App\Http\Resources\UserResource as UserResource;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('id','desc')->paginate(15);
        return UserResource::collection($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserValidator $request)
    {
        $validated = $request->validated();
        $data = array_merge($validated, ["password" => bcrypt($request->password)]);
        $user = User::create($data);
        return response()->json([
            "message" => "User registered successfully",
            "user"=>$user ],
            201);
       
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }
    /**
     * Login user and create token
     */
      public function login(UserLoginRequest $request) {
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials)){
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if($request->remember_me){
            $token->expires_at = Carbon::now()->addWeeks(1);
            $token->save();

            return response()->json([
                'access_token' => $tokenResult->accessToken,
                'token_type' => Carbon::parse(
                    $tokenResult->token->expires_at)->toDateTimeString()
                
            ]);    
        }
        
      } 
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $req) {
        $request->user()->revoke();
        return response()->json([
            "message" => "Successfully logged out"
        ]);
    }
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $req) {
        return response()->json($req->user());
    }
 

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserValidator $request, $id)
    {
        $user = User::findOrFail($id);
        $data = array_merge($request->validated());
        $user->update($data);
        $s= trans('messages.updated');
        return response()->json([
            "message" => $s,
            "user"=>$user],
            201);
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->softDelete();
        return response()->json(["message" => "User is deleted"], 200);
    }
   
}
