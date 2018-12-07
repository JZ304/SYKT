<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Overtrue\LaravelWeChat\Facade;

class AuthController extends Controller {

    private $app;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login']]);
        $this->app = Facade::miniProgram();
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {
        //return 1111;die;
        /*$user_session = $this->app->auth->session($request->jsCode);
        if (!$user_session['openid']){
            return $this->errorBadRequest('jsCode错误');
        }*/
        /*$user = User::firstOrCreate(['openid' => $user_session['openid']],[
            'unionid' => $user_session['unionid'],
            'nickName' => $request->nickName,
            'avatarUrl' => $request->avatarUrl,
            'gender' => $request->gender,
            'country' => $request->country,
            'province' => $request->province,
            'city' => $request->city,
            'language' => $request->language
        ]);*/
        $user = User::firstOrCreate(['openid' => 'huang'],[
            'unionid' => 'huang',
            'nickName' => 'huangyu',
            'avatarUrl' => '',
            'gender' => 1,
            'country' => '中国',
            'province' => '四川',
            'city' => '成都',
            'language' => 'zh_HK'
        ]);
        //将昵称和头像存入缓存3600
        Cache::put('avatarUrl'.$user->id,$user->avatarUrl,60);
        Cache::put('nickName'.$user->id,$user->nickName,60);
        $token = Auth::login($user);
        //$token = auth('api')->attempt($user);
        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me() {
//	    $user = auth()->user();
        //dump(auth());die;
        $user_id = auth()->id();

        $user = User::where('id',$user_id)->first();

        if(empty($user)){
            return $this->errorNotFound('未找到该用户');
        }

        //return $this->json(['id'=>$user->id, 'nickname'=>$user->nickName]);
        return $this->dataResponse(['id'=>$user->id, 'nickname'=>$user->nickName,
            'avatarUrl'=>$user->avatarUrl,
            'prize' => $user->prize,
            'is_info' => $user->is_info,
            'capacity' => $user->capacity]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();
        return $this->successResponse('退出成功！');
        //return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token) {
        return response()->json([
            'status' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }
    /*
     * 修改过的代码
     * */
}
