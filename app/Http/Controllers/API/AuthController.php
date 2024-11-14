<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Traits\SendEmailTrait;
use Carbon\Carbon;

class AuthController extends Controller
{
    use SendEmailTrait;

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:15|unique:users',
            'password' => 'required|confirmed|string|min:8',
            'photo' => 'nullable|image|max:2048',  // Optional photo field
        ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->first();
            return response()->json(['status' => false, 'msg' => $firstError, 'data' => null, 'notes' => ['Invalid data']], 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->email,
            'phone' => $request->get('phone'),
            'password' => Hash::make($request->password),
            'is_email_verified' => true
        ]);

        if ($request->photo)
            $user->update([
                'photo' => $request->file('photo') ? $request->file('photo')->store('photos', 'public') : null,
            ]);

        // Get the full path of the photo
        $photoPath = $user->photo;

        // If the photo exists, get the path after the domain
        if ($photoPath) {
            $photoUrl = asset('storage/' . $user->photo);
            $user->photo = $photoUrl;
        }

        $token = $user->createToken('token')->plainTextToken;


        $code = rand(100000, 999999);

        $user->email_last_verfication_code = Hash::make($code);
        $user->email_last_verfication_code_expird_at = Carbon::now()->addMinutes(10)->timezone('Europe/Istanbul');
        $user->save();

        $msg_title = "تفضل رمز تفعيل بريدك الالكتروني";
        $msg_content = "<h1>رمز التاكيد هو <span style='color: blue'>" . $code . "</span></h1>";

        // $this->sendEmail($user->email, $msg_title, $msg_content);

        $user->token = $token;

        return response()->json(['status' => true, 'msg' => 'User registered successfully', 'data' => ['user' => $user], 'notes' => ['User registered successfully']], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->first();
            return response()->json(['status' => false, 'msg' => $firstError, 'data' => null, 'notes' => ['Invalid data']], 400);
        }

        $credentials = $request->only('email', 'password');

        if (!auth()->attempt($credentials)) {
            return response()->json(['status' => false, 'msg' => 'Invalid credentials', 'data' => null, 'notes' => ['Invalid credentials']], 401);
        }

        $user = User::where("email", $request->email)->first();
        // Get the full path of the photo
        $photoPath = $user->photo;

        // If the photo exists, get the path after the domain
        if ($photoPath) {
            $photoUrl = asset('storage/' . $user->photo);
            $user->photo = $photoUrl;
        }

        $token = $user->createToken('token')->plainTextToken;

        return response()->json(['status' => true, 'msg' => 'Login successful', 'data' => ['token' => $token, 'user' => $user], 'notes' => ['Login successful']], 200);
    }

    public function profile(Request $request)
    {
        $application = Application::with(['rates.user', 'user'])->where('user_id', $request->user()->id)->first();

        $user = $request->user();
        // Get the full path of the photo
        $photoPath = $user->photo;

        // If the photo exists, get the path after the domain
        if ($photoPath) {
            $photoUrl = asset('storage/' . $user->photo);
            $user->photo = $photoUrl;
        }

        if ($application) {
            $user->video_1 = $application->video_1 ? asset('storage/' . $application->video_1) : $application->video_1;
            $user->video_2 = $application->video_2 ? asset('storage/' . $application->video_2) : $application->video_2;
            $user->is_approved = $application->is_approved;
            $user->rate_video_1 = $application->ratesForVideo('video_1')->sum('rate') ?? 0;
            $user->rate_video_2 = $application->ratesForVideo('video_2')->sum('rate') ?? 0;
        }

        return response()->json(['status' => true, 'msg' => 'User profile fetched', 'data' => ['user' => $user], 'notes' => ['User profile fetched']], 200);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15|unique:users,phone,' . $user->id,
            'photo' => 'nullable|image',
        ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->first();
            return response()->json(['status' => false, 'msg' => $firstError, 'data' => null, 'notes' => ['Invalid data']], 400);
        }

        $user->update($request->only(['name', 'phone']));
        if ($request->hasFile('photo')) {
            $user->update([
                'photo' => $request->file('photo') ? $request->file('photo')->store('photos', 'public') : null,
            ]);
        }

        // Get the full path of the photo
        $photoPath = $user->photo;

        // If the photo exists, get the path after the domain
        if ($photoPath) {
            $photoUrl = asset('storage/' . $user->photo);
            $user->photo = $photoUrl;
        }

        return response()->json(['status' => true, 'msg' => 'Profile updated successfully', 'data' => ['user' => $user], 'notes' => ['Profile updated successfully']], 200);
    }


    public function askEmailCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->first();
            return response()->json(['status' => false, 'msg' => $firstError, 'data' => null, 'notes' => ['Invalid data']], 400);
        }

        $email = $request->get('email');

        if ($email) {
            $code = rand(100000, 999999);


            $msg_title = "تفضل رمز تفعيل بريدك الالكتروني";
            $msg_content = "<h1>رمز التاكيد هو <span style='color: blue'>" . $code . "</span></h1>";

            $this->sendEmail($email, $msg_title, $msg_content);

            return response()->json(['status' => true, 'msg' => 'تم ارسال رمز التحقق بنجاح عبر الايميل', 'data' => ['code' => $code], 'notes' => ['code expires after 10 minutes']], 200);
        }

        return response()->json(['status' => false, 'msg' => 'Invalid process', 'data' => [], 'notes' => ['code expires after 10 minutes']], 400);
    }

    public function verifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "code" => ["required"],
        ], [
            "code.required" => "ادخل رمز التاكيد ",
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => $validator->errors()->first(), 'data' => [], 'notes' => []], 400);
        }

        $user = $request->user();
        $code = $request->code;

        if ($user && Hash::check($code, $user->email_last_verfication_code)) {
            $verificationTime = new Carbon($user->email_last_verfication_code_expird_at, 'Europe/Istanbul');
            if ($verificationTime->isPast()) {
                return response()->json(['status' => false, 'msg' => 'الرمز غير ساري', 'data' => [], 'notes' => []], 400);
            }

            $user->is_email_verified = true;
            $user->save();

            return response()->json(['status' => true, 'msg' => 'تم تاكيد بريدك الالكتروني بنجاح', 'data' => [], 'notes' => []], 200);
        }

        return response()->json(['status' => false, 'msg' => 'الرمز غير صحيح', 'data' => [], 'notes' => []], 400);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "old_password" => ["required"],
            'password' => [
                'required',
                'min:8',
                'confirmed'
            ],
        ], [
            "old_password.required" => "ادخل كلمة المرور الحالية",
            "password.required" => "ادخل كلمة المرور",
            "password.min" => "يجب ان تكون كلمة المرور من 8 احرف على الاقل",
            "password.regex" => "يجب ان تحتوي كلمة المرور علي حروف وارقام ورموز",
            "password.confirmed" => "كلمة المرور والتاكيد غير متطابقان",
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => $validator->errors()->first(), 'data' => [], 'notes' => []], 400);
        }

        $user = $request->user();
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['status' => false, 'msg' => 'كلمة المرور الحالية غير صحيحة', 'data' => [], 'notes' => []], 400);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['status' => true, 'msg' => 'تم تغير كلمة المرور بنجاح', 'data' => [], 'notes' => []], 200);
    }

    public function askEmailCodeForgot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => ["required", "email"],
        ], [
            "email.required" => "يرجى إدخال البريد الإلكتروني الخاص بك",
            "email.email" => "يرجى إدخال عنوان بريد إلكتروني صالح",
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => $validator->errors()->first(), 'data' => null], 400);
        }

        $user = User::where("email", $request->email)->first();

        if (!$user) {
            return response()->json(['status' => false, 'msg' => 'هذا الحساب غير مسجل لدينا', 'data' => null], 404);
        }

        $code = rand(100000, 999999);
        $user->email_last_verfication_code = Hash::make($code);
        $user->email_last_verfication_code_expird_at = now()->addMinutes(10);
        $user->save();

        // Send Email (implement email logic)
        $this->sendEmail($user->email, "كود استعادة كلمة المرور", "كود استعادة كلمة المرور الخاص بك هو: $code");

        return response()->json(['status' => true, 'msg' => 'تم إرسال الكود بنجاح إلى بريدك الإلكتروني', 'data' => null], 200);
    }

    public function checkCodeForgot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => ["required", "email"],
            "code" => ["required"],
        ], [
            "email.required" => "يرجى إدخال البريد الإلكتروني الخاص بك",
            "email.email" => "يرجى إدخال عنوان بريد إلكتروني صالح",
            "code.required" => "يرجى إدخال الكود المرسل",
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => $validator->errors()->first(), 'data' => null], 400);
        }

        $user = User::where("email", $request->email)->first();

        if (!$user || !Hash::check($request->code, $user->email_last_verfication_code)) {
            return response()->json(['status' => false, 'msg' => 'الكود أو البريد الإلكتروني غير صحيح', 'data' => null], 400);
        }

        if (now()->gt($user->email_last_verfication_code_expird_at)) {
            return response()->json(['status' => false, 'msg' => 'الكود المرسل منتهي الصلاحية', 'data' => null], 400);
        }

        return response()->json(['status' => true, 'msg' => 'الكود صحيح ويمكنك الآن تعيين كلمة المرور الجديدة', 'data' => null], 200);
    }

    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => ["required", "email"],
            "code" => ["required"],
            "password" => [
                "required",
                "min:8",
                "confirmed"
            ],
        ], [
            "email.required" => "يرجى إدخال البريد الإلكتروني الخاص بك",
            "email.email" => "يرجى إدخال عنوان بريد إلكتروني صالح",
            "code.required" => "يرجى إدخال الكود المرسل",
            "password.required" => "يرجى إدخال كلمة المرور الجديدة",
            "password.min" => "يجب أن تكون كلمة المرور 8 أحرف على الأقل",
            "password.regex" => "يجب أن تحتوي كلمة المرور على حروف وأرقام ورموز خاصة",
            "password.confirmed" => "تأكيد كلمة المرور غير مطابق",
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => $validator->errors()->first(), 'data' => null], 400);
        }

        $user = User::where("email", $request->email)->first();

        if (!$user || !Hash::check($request->code, $user->email_last_verfication_code)) {
            return response()->json(['status' => false, 'msg' => 'الكود أو البريد الإلكتروني غير صحيح', 'data' => null], 400);
        }

        if (now()->gt($user->email_last_verfication_code_expird_at)) {
            return response()->json(['status' => false, 'msg' => 'الكود المرسل منتهي الصلاحية', 'data' => null], 400);
        }

        // Reset the password
        $user->password = Hash::make($request->password);
        $user->email_last_verfication_code = null; // Clear the code after use
        $user->save();

        return response()->json(['status' => true, 'msg' => 'تم تغيير كلمة المرور بنجاح', 'data' => null], 200);
    }
}
