<?php
namespace App\Http\Controllers;

use App\Cardtemplate;
use App\Card;
use App\Like;
use App\User;
use App\Usertext;
use TCG\Voyager\Models\Role;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


use Illuminate\Foundation\Auth\AuthenticatesUsers;

class PersonalController extends Controller
{
    use AuthenticatesUsers;

    public function newuser(Request $request, $id)
    {

        $template = Cardtemplate::findOrFail($id);
        $request->session()->put('template', $id);

        if($request->getMethod() == "POST" && empty($request->input('g-recaptcha-response')))
        {
            $val = 'Wrong CAPTCHA';
        }
        else if($request->getMethod() == "POST")
        {
            $val = Validator::make($request->input(), ['email' => 'required|unique:users,email'])->messages()->getMessages();

            if(empty($val))
            {
                $password = hash('joaat', time());
                $user = User::create([
                    'name'     =>  $request->input('email'),
                    'email'    => $request->input('email'),
                    'password' => Hash::make($password)
                ]);

                $regular_role = Role::findOrFail(2);
                $user->roles()->attach($regular_role);

                Auth::loginUsingId($user->id, true);

                return view('personal.usercreated', ['password' => $password, 'id_template' => $id]);
            }
        }

        return view('personal.newuser', ['id_template' => $id, 'errors' => $val['email'][0]??$val??'', 'email'=>$request->input('email')]);
    }

    public function login(Request $request)
    {
        $cred = $request->only(['email', 'password']);

        if ($request->getMethod() == "POST" && !empty($request->input('g-recaptcha-response')) && Auth::attempt($cred)) {
            if ($request->session()->exists('template'))
                return redirect()->route("personal.edit", $request->session()->get('template'));
            else
                return redirect()->route("personal.cards");
        }
        else if($request->getMethod() == "POST" && empty($request->input('g-recaptcha-response')))
        {
            $val = 'Wrong CAPTCHA';
        }
        else if($request->getMethod() == "POST")
        {
            $val = 'Wrong username or password';
        }

        return view('personal.login', ['errors' => $val??'']);

    }

    public function cards(Request $request)
    {
        $user = Auth::user();

        $cards = Card::where('id_user', $user->id);
        $cards = Card::all();

        return view('personal.list', ['cards' => $cards]);
    }

    public function editcard(Request $request, $id)
    {
        $user = Auth::user();
        $template = Cardtemplate::findOrFail($id);
        $card = Card::where('id_user', $user->id)->where('id_cardtemplate', $id)->first();

        if($request->getMethod() == "POST") {

            if(empty($card))
            {
                $card = new Card;
                $card->id_user = $user->id;
                $card->id_cardtemplate = $id;
                $card->url = hash('joaat', $user->id . $id . time());
                $card->state = 1;
                $card->save();
            }

            foreach ($request->input('frametext') as $fkey => $customframe)
            {
                $cframe = Usertext::where('id_card', $card->id_card)->where('id_frame', $fkey)->first();

                $validator = Validator::make(['content_text' => $customframe, 'header_text' => ($request->input('frameheader')[$fkey]??'')], Usertext::$rules);
                if ($validator->fails()) {
                    $val = $validator->getMessageBag()->messages();
                    return view('personal.edit', ['template' => $template, 'id_card' => $card->id_card??0, 'errors' => array_shift($val)[0]]);
                }

                if(empty($cframe))
                {
                    $cframe = new Usertext;
                    $cframe->id_card = $card->id_card;
                    $cframe->id_frame = $fkey;
                    $cframe->id_user = $user->id;
                    //$cframe->save();
                }

                $cframe->content_text = $customframe??'';
                $cframe->header_text = $request->input('frameheader')[$fkey]??'';
                $cframe->save();

            }

            return redirect()->route("personal.cards");
        }

        return view('personal.edit', ['template' => $template, 'id_card' => $card->id_card??0]);
    }

    public function logout()
    {
        Auth::logout();
    }

}