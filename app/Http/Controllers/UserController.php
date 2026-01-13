<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\ContractType;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index', 'store']]);
        // $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $roles = Role::where('name','!=','System Administrator')->get();
        return view('pages.users.index',compact('roles'));
    }

    public function usersBonus(Request $request)
    {
        return view('admin.pages.users.users_bonus');
    }

    public function employeeRatings(Request $request)
    {
        return view('admin.pages.users.employee_ratings');
    }

    public function getUsersForAjax(Request $request)
    {
        $users = User::query()->latest();
        if($request->role_id){
            $users = $users->role($request->role_id);
        }
        return Datatables()->of($users)
            ->addIndexColumn()
            ->addColumn('user_full_name', function ($data) {
                $html = $data->full_name;
                if($data->hasRole('საბითუმო')){
                    $html.= '<br><br> '.$data->company_name.' '.$data->identification_code;
                }
                return $html;
            })
            ->addColumn('role', function ($data) {
                $html = '';
                foreach ($data->getRoleNames() as $v) {
                    $html .= "<span class='badge rounded-pill bg-success'>$v</span>";
                }
                return $html;
            })

            ->addColumn('action', function ($data) {
                $btn = '';
                $btn = '<a class="btn btn-primary shadow btn-xs sharp mr-1" href="' . route("users.report.settings",$data->id) . '" data-bs-toggle="tooltip" data-bs-placement="top" title="რეპორტის პარამეტრები" data-status="balance-plus"><i class="fa fa-list"></i></a>';
                $btn .= ' <a class="btn btn-primary shadow btn-xs sharp mr-1" href="' . route("users.edit",$data->id) . '" data-bs-toggle="tooltip" data-bs-placement="top" title="რედაქტირება"><i class="fa fa-edit"></i></a>';
                if($data->status == 1){
                    $btn .= ' <a class="btn btn-danger shadow btn-xs sharp mr-1" href="javascript:void(0)" onclick="disableUser(' . $data->id . ')"><i class="fa fa-times"></i></a>';
                }

//                if (currentUser()->can('user-delete')) {
//                $btn .= ' <a class="btn btn-danger shadow btn-xs sharp mr-1" href="javascript:void(0)" onclick="deleteUser(' . $data->id . ')"><i class="fa fa-trash"></i></a>';
//                }
                return $btn;
            })
            ->rawColumns(['user_counts', 'action','user_full_name','role'])
            ->make(true);
    }

    public function getUsersBalanceForAjax(Request $request)
    {
        $users = User::query()->where('bonus_balance','>',0)->latest();
        return Datatables()->of($users)
            ->addIndexColumn()
            ->addColumn('formatted_bonus_balance', function ($data) {
                $html = number_format($data->bonus_balance,2).' ₾';
                return $html;
            })
            ->rawColumns(['formatted_bonus_balance', 'action','role'])
            ->make(true);
    }

    public function getEmployeeRatingsForAjax(Request $request)
    {
        $users = User::query()->role(5)->whereNotNull('user_id')->with('polls')->latest();
        return Datatables()->of($users)
            ->addIndexColumn()
            ->addColumn('formatted_user', function ($data) {
                $html = $data->full_name;
                return $html;
            })
            ->addColumn('formatted_user_tel', function ($data) {
                $html = $data->tel;
                return $html;
            })

            ->addColumn('action', function ($data) {
                $btn = '';
//                $btn .= ' <a class="btn btn-primary shadow btn-xs sharp mr-1" href="' . route("admin.users.show.employee.ratings",$data->id) . '"><i class="fa fa-eye"></i></a>';
                return $btn;
            })
            ->rawColumns(['action','role'])
            ->make(true);
    }

    public function showEmployeeRating($id)
    {
        $user = User::query()->findOrFail($id);
        return view('admin.pages.users.show',compact('user'));

    }

    public function usersBonusRSExportExcel(){
        return Excel::download(new UsersBonusRSExport(), 'RS-ის რეპორტი.xlsx');
    }

    public function usersBonusBankExportExcel(){
        return Excel::download(new UsersBonusBankExport(), 'საბანკო რეპორტი.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $roles = Role::where('name','!=','System Administrator')->pluck('name', 'name')->all();
        $companies = Company::all();
        return view('pages.users.create', compact('roles','companies'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'surname' => 'required',

            'tel' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        $companiesData = [];

        foreach ($request->input('companies', []) as $companyId => $settings) {
            $companiesData[$companyId] = [
                'receive_report' => false,
            ];
        }

        $user->companies()->sync($companiesData);

        return redirect()->to(route('users.index'))
            ->with('success', 'მომხმარებელი წარმატებით დარეგისტრირდა');
    }

    public function customerBalances(){
        return view('admin.pages.users.customer_balances');
    }

    public function renderRelationData(Request $request)
    {
        $id = $request->id;
        $user = User::findOrFail($id);
        $orders = [];
        $users = [];

        $orders = Order::where('user_id',$id)->where('payment_status',2)->get();
        $users = User::where('parent_user_id',$id)->get();


        return jsonResponse(['status' => 0,'html' => view('admin.general.users.relation_data',compact('users','orders','id'))->render()]);
    }

    public function show($id)
    {
        $user = User::find($id);
        return view('pages.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::where('name','!=','System Administrator')->pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
//        return $roles;
        return view('pages.users.edit', compact('user', 'roles', 'userRole'));
    }

    public function reportSettings($id)
    {
        $user = User::find($id);
        $companies = Company::all();
        $contractTypes = ContractType::all();

        return view('pages.users.report-settings', compact('user', 'companies', 'contractTypes'));
    }

    public function updateReportSettings(Request $request, User $user): RedirectResponse
    {
        // =========================
        // COMPANIES SYNC
        // =========================
        $companiesData = [];

        foreach ($request->input('companies', []) as $companyId => $settings) {
            $companiesData[$companyId] = [
                'receive_report' => isset($settings['receive_report']),
            ];
        }

        $user->companies()->sync($companiesData);

        // =========================
        // CONTRACT TYPES SYNC
        // =========================
        $typesData = [];

        foreach ($request->input('contract_types', []) as $typeId => $settings) {
            $typesData[$typeId] = [
                'receive_report' => isset($settings['receive_report']),
            ];
        }

        $user->contractTypes()->sync($typesData);

        // =========================
        // BACK WITH MESSAGE
        // =========================
        return redirect()
            ->back()
            ->with('success', 'რეპორტის პარამეტრები წარმატებით შეინახა');
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'surname' => 'required',
//            'department_id' => 'required',
//            'tel' => 'required',
//            'personal_num' => 'required',
            'email' => '',
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
//        return $input;
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->to(route('users.index'))
            ->with('success', 'მომხმარებლის ინფორმაცია წარმატებით შეიცვალა');
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }

    public function disableUser(Request $request)
    {
        // return $request->all();
        try {
            $user = User::findOrFail($request->id);
            // $cityId = $user->city_id;
            // $districtId = $user->district_id;
            $user->update(['status' => 0]);
            // $userAgent = $request->agent_id;

            // $parcels = Property::where('agent_id',$request->id)->get();
            // foreach($parcels as $parcel){
            // $parcel->update(['agent_id' => $userAgent]);
            // }
            return jsonResponse(['status' => 1]);
        } catch (\Exception $exception) {
            return jsonResponse(['status' => 0,'err' => $exception->getMessage()]);
        }
    }

    public function deleteUser(Request $request)
    {
        try {
            User::findOrFail($request->id)->delete();
            return jsonResponse(['status' => 1]);
        } catch (\Exception $exception) {
            return jsonResponse(['status' => 0]);
        }
    }
}
