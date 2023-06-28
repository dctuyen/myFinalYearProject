<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\Constants;
use App\Library\Helper;
use App\Models\Classes;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Shift;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $data = [];

        $data['uinfo'] = auth()->user();
        $data['activeSidebar'] = 'home';
        return view('admin.home')->with($data);
    }

    /**
     * @throws Exception
     */
    public function createDataUser(): void
    {
//        $this->createRandomClassData();
        $this->createEnrollments();
        return;
        $json = file_get_contents(public_path() . '\datatest.json');
        $objects = json_decode($json, false, 512, JSON_THROW_ON_ERROR);

        $sdt = [
            '081', '082', '083', '084', '085',
            '031', '032', '033', '034', '035', '036', '037', '038', '039',
            '070', '076', '077', '078', '079'
        ];
        $minBirthday = strtotime('1998-01-01');
        $maxBirthday = strtotime('2005-12-31');
        $birthday = random_int($minBirthday, $maxBirthday);
        $address =
            [
                'Tỉnh Lào Cai', 'Yên Bái', 'Điện Biên', 'Hoà Bình', 'Lai Châu', 'Sơn La', 'Tỉnh Hà Giang', 'Thái Bình',
                'Cao Bằng', 'Bắc Kạn', 'Lạng Sơn', 'Tuyên Quang', ' Thái Nguyên', 'Phú Thọ', 'Bắc Giang', 'Quảng Ninh',
                'Tỉnh Bắc Ninh', 'Hà Nam', 'Hà Nội', 'Hải Dương', 'Hải Phòng', 'Hưng Yên', 'Nam Định', 'Ninh Bình',
                'Vĩnh Phúc'
            ];
        $count = 0;
        foreach ($objects as $ob) {
            if ($count >= 50) {
                break;
            }
            $rand = random_int(1, 2);
            $data = get_object_vars($ob);
            $email = 'carer' . strtolower(str_replace(' ', '', Helper::khongdau($data['full_name']))) . '@gmail.com';
            $checkExits = User::where('email', '=', $email)->count();
            if ($checkExits > 0) {
                continue;
            }

            $sex = 'male';
            if ($rand === 1) {
                $sex = 'female';
            }
            $phone = $sdt[array_rand($sdt)] . random_int(1000000, 9999999);
            $add = $address[array_rand($address)];
            User::create([
                'first_name' => $data['last_name'],
                'last_name' => $data['first_name'],
                'sex' => $sex,
                'birthday' => date('Y-m-d', $birthday),
                'address' => $add,
                'email' => $email,
                'phone' => $phone,
                'role_id' => 2,
                'status' => Constants::ACTIVATED_STATUS,
                'password' => Hash::make('12345678'),
                'remember_token' => Helper::getRandomText(40),
                'created_at' => Helper::getCurrentTime(),
                'creator' => 1,
                'certificate' => '{}'
            ]);
            $count++;
        }
    }

    public function createRandomClassData()
    {
        for ($i = 0; $i < 80; $i++) {
            $teacher = User::where('role_id', '=', 3)->inRandomOrder()->first()->id;
            $carer_id = User::where('role_id', '=', 4)->inRandomOrder()->first()->id;
            while (true) {
                $course = Course::inRandomOrder()->first();
                $shift_id = Shift::where('id', '!=', '')->inRandomOrder()->first();
                $check = Classes::where('shift_id', '=', $shift_id->id)->where('course_id', '=', $course->id)->count();
                if ($check < 1) break;
            }


            $startDate = strtotime('2023-04-01');
            $endDate = strtotime('2023-06-31');
            $randomTimestamp = mt_rand($startDate, $endDate);

            $randomDate = date('Y-m-d', $randomTimestamp);

            $modifiedTimestamp = strtotime("+$course->duration months", $randomTimestamp);
            $modifiedDate = date('Y-m-d', $modifiedTimestamp);

            $randomString = strtoupper(substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(5 / strlen($x)))), 1, 5)) .
                strtoupper(substr(str_shuffle(str_repeat($x = '0123456789', ceil(5 / strlen($x)))), 1, 5));
            $textname = strtoupper(explode(' ', $course->name)[0]) . $randomString;


            Classes::create([
                'name' => $textname,
                'teacher_id' => $teacher,
                'carer_id' => $carer_id,
                'course_id' => $course->id,
                'shift_id' => $shift_id->id,
                'start_date' => $randomDate,
                'end_date' => $modifiedDate,
                'status' => Constants::ACTIVATED_STATUS,
                'number_of_students' => 30,
                'created_at' => $randomDate,
            ]);
        }
    }

    /**
     * @throws Exception
     */
    public function createEnrollments()
    {
        for ($i = 0; $i < 80; $i++) {
            while (true) {
                $student = User::where('role_id', '=', 2)->inRandomOrder()->first()->id;
                $class = Classes::where("start_date", '<=', date('Y-m-d'))->inRandomOrder()->first();
                $classesInCourse = Classes::where('course_id', $class->course_id)->get();
                $exist = false;
                foreach ($classesInCourse as $cl) {
                    $check = Enrollment::where('student_id', '=', $student)
                        ->where('class_id', '=', $cl->id)->count();
                    if ($check > 0) {
                        $exist = true;
                    }
                }

                $check = Enrollment::where('student_id', '=', $student)
                    ->where('class_id', '=', $class->id)->count();
                if ($check < 1 && !$exist) {
                    break;
                }
            }

            $startDate = strtotime('2023-04-01');
            $endDate = strtotime('2023-06-25');
            $registrationDate = mt_rand($startDate, $endDate);
            Enrollment::create([
                'class_id' => $class->id,
                'student_id' => $student,
                'registration_date' => date('Y-m-d', $registrationDate),
                'grade' => random_int(5, 10),
                'status' => Constants::ACTIVATED_STATUS
            ]);

        }
    }
}
