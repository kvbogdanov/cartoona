<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Cardtemplate;
use App\Card;
use App\Like;

class TemplateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected function getJson($template,$id_card=false)
    {
        $json = [];
        foreach ($template->frames as $key => $frame)
        {
            if ($frame->visible==0)
                continue;

            if (!empty($frame->getMedia('audio')))
            {
                $audios = [];

                foreach ($frame->getMedia('audio') as $akey => $data)
                    $audios[] = $data->getUrl();
            }

            if (empty($audios))
                $audios = '';

            // svg wrap
            $imgUrl = $frame->getFirstMediaUrl();
            if(!strpos($imgUrl, '.svg'))
                $imgUrl = (!empty($frame->getMedia()[0]))?$frame->getMedia()[0]->getUrl('large'):'';

            $json[] = [
                'title'     => $frame->userheader($id_card??0),
                'img'       => $imgUrl,
                'text'      => $frame->usertext($id_card??0),
                'id_frame'  => $frame->id_frame,
                'audios'    => $audios,
                'effect'    => $frame->effect,
                'effectOut' => $frame->effect_out,
            ];
        }

        return $json;
    }
    public function view($id)
    {
        $template = Cardtemplate::findOrFail($id);

        return view('template/view',[
            'template'=>$template,
            'json'=>$this->getJson($template),
        ]);
    }

    public function index()
    {
        return redirect('/14february');
        $templates = Cardtemplate::all();
        return view('template/index',['templates'=>$templates]);
    }

    public function viewalias($alias)
    {
        $template = Cardtemplate::whereRaw("LOWER(url) = '" . strtolower($alias) . "'")->whereRaw("status&".Cardtemplate::STATE_ACTIVE)->first();

        if(!$template)
            return $this->viewsubalias(false, $alias);

        return view('template/view',[
            'template'=>$template,
            'json'=>$this->getJson($template),
        ]);
    }

    public function viewsubalias($alias, $subalias)
    {
        $card = Card::where('url', $subalias)->whereRaw("state&".Card::STATE_PAYED)->firstOrFail();

        if( $card->daysLeft() < 0)
            abort(404);
        /*
        if($card->cardtemplate->url != $alias)
        {
            // тут будет ошибкаif ($frame->visible==0)
                continue;
        }*/

        $template = $card->cardtemplate;

        return view('template/view',[
            'template'=>$template,
            'id_card' => $card->id_card,
            'url' => $card->url,
            'json' => $this->getJson($template, $card->id_card)
        ]);
    }

    public function like($id)
    {
        $template = Cardtemplate::findOrFail($id);

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $like = Like::where('ip',ip2long($ip))->where('id_cardtemplate',$id)->first();

        $type = 0;

        if (!empty($_POST['l']))
            $type = (int)$_POST['l'];

        $insert = false;

        if (!empty($like) && $like->type!=$type)
        {
            if ($type==1)
            {
                $template->unlike--;
                $template->like++;
            }
            else
            {
                $template->unlike++;
                $template->like--;
            }

            $like->delete();
            $insert = true;
        }
        elseif (empty($like))
        {
            if ($type==1)
                $template->like++;
            else
                $template->unlike++;

            $insert = true;
        }
        elseif (!empty($like))
        {
            if ($type==1)
                $template->like--;
            else
                $template->unlike--;
     
            $like->delete();
            $template->save();
        }

        if ($insert == true)
        {
            $model = new Like;
            $model->ip = ip2long($ip);
            $model->type = $type;
            $model->id_cardtemplate = $id;
            $model->save();

            $template->save();
        }

        return json_encode([
            'like'=>$template->like,
            'unlike'=>$template->unlike,
        ]);
    }
}