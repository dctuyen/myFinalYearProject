<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\Constants;
use App\Library\Helper;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
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
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $data = [];

        $data['uinfo'] = auth()->user();
        return view('admin.home')->with($data);
    }

    /**
     * @throws Exception
     */
    public function studentManagement(): Factory|View|Application
    {
//        $allStudent = User::where('role_id', '=', Constants::STUDENT_ROLE_ID)->get();
//        foreach ($allStudent as $student) {
//            $name = $student->first_name . $student->last_name;
//            $student->email = str_replace(' ', '', strtolower(Helper::khongdau($name))) . '@gmail.com';
//            $student->save();
//        }
        $allStudent = User::where('role_id', '=', Constants::STUDENT_ROLE_ID)
            ->orderBy('id')->paginate(20);
        $data = [
            'uinfo' => auth()->user(),
            'students' => $allStudent,
            'activeSidebar' => 'studentmanagement'
        ];
        return view('admin.studentmanagement')->with($data);
    }

    /**
     * @throws Exception
     */
    public function createDataUser(): void
    {
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
            $data = json_encode($ob);

            $sex = 'male';
            if ($rand === 1) {
                $sex = 'female';
            }
            $name = $data['first_name'] . $data['last_name'];
            $email = strtolower(Helper::khongdau($name)) . '@gmail.com';
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
                'role_id' => Constants::STUDENT_ROLE_ID,
                'status' => Constants::ACTIVATED_STATUS,
                'password' => Hash::make('12345678'),
                'remember_token' => Helper::getRandomText(40),
                'created_at' => Helper::getCurrentTime()
            ]);
            $count++;
        }
    }
}
