<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Validation\Rule;

use App\Cardtemplate;

use TCG\Voyager\Database\Schema\SchemaManager;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Events\BreadDataDeleted;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Events\BreadImagesDeleted;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\Traits\BreadRelationshipParser;


class CardtemplateController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    private $slug = 'Cardtemplate';

	public function create(Request $request)
	{
        $slug = $this->slug;
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        // Check permission
        $this->authorize('add', app($dataType->model_name));

        // сразу создаём шаблон, чтобы потом к нему без проблем привязать фреймы
        // если передумаем что-то привязывать и редактировать - удалим
        $dataTypeContent = new Cardtemplate;
        $dataTypeContent->id_user = auth()->user()->id;
        $dataTypeContent->save();

        $dataTypeContent->name = 'Template ' . $dataTypeContent->id_cardtemplate;
        $dataTypeContent->save();

		$view = 'voyager::bread.create';
        $slug = strtolower($slug);
        if (view()->exists("voyager::$slug.create")) {
            $view = "voyager::$slug.create";
        }

		return Voyager::view($view, compact('dataType', 'dataTypeContent'));
	}

	public function duplicate(Request $request, $id)
    {
        $sourceTemplate = Cardtemplate::findOrFail($id);
        $targetTemplate = $sourceTemplate->replicate();
        $targetTemplate->url =  $sourceTemplate->url."-".date("Y-m-d");
        $targetTemplate->name =  $sourceTemplate->name."- Copy";
        $targetTemplate->push();

        if($sourceTemplate->getFirstMediaPath() && file_exists($sourceTemplate->getFirstMediaPath()))
            $targetTemplate->addMedia($sourceTemplate->getFirstMediaPath())->preservingOriginal()->toMediaCollection();

        foreach ($sourceTemplate->frames as $sourceFrame)
        {
            $targetFrame = $sourceFrame->replicate();
            $targetFrame->id_cardtemplate = $targetTemplate->id_cardtemplate;
            $targetFrame->push();
            if($sourceFrame->getFirstMediaPath() && file_exists($sourceFrame->getFirstMediaPath()))
                $targetFrame->addMedia($sourceFrame->getFirstMediaPath())->preservingOriginal()->toMediaCollection();

            if($sourceFrame->getFirstMediaPath('audio') && file_exists($sourceFrame->getFirstMediaPath('audio')))
                $targetFrame->addMedia($sourceFrame->getFirstMediaPath('audio'))->preservingOriginal()->toMediaCollection('audio');
        }

        return redirect()
            ->route("voyager.cardtemplate.edit", $targetTemplate->id_cardtemplate)
            ->with([
                'message'    => "Successfully duplicated Card template",
                'alert-type' => 'success',
            ]);
    }

    public function edit(Request $request, $id)
    {
        $slug = $this->slug;
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        // Check permission
        $this->authorize('add', app($dataType->model_name));

        $dataTypeContent = app($dataType->model_name)->findOrFail($id);

        $view = 'voyager::bread.create';
        $slug = strtolower($slug);
        if (view()->exists("voyager::$slug.create")) {
            $view = "voyager::$slug.create";
        }

        return Voyager::view($view, compact('dataType', 'dataTypeContent'));
    }

    public function update(Request $request, $id)
    {
        $slug = $this->slug;
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        // Compatibility with Model binding.
        $id = $id instanceof Model ? $id->{$id->getKeyName()} : $id;
        $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);
        // Check permission
        $this->authorize('edit', $data);
        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->editRows, $dataType->name, $id);

        //$customVal  = Validator::make($request->input(), Cardtemplate::$rules);
        $customVal = Validator::make($request->input(), [
            'url' => [
                'nullable',
                Rule::unique('db_cardtemplate')->ignore($id, 'id_cardtemplate'),
            ],
        ]);


        if ($val->fails()) {
            return response()->json(['errors' => $val->messages()]);
        }
        if ($customVal->fails()) {
            return response()->json(['errors' => $customVal->messages()]);
        }

        if (!$request->ajax()) {
            $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

            if(!empty($request->input('framesort')))
            {
                $framesort = explode(",", $request->input('framesort'));

                foreach ($framesort as $ord => $pos) {
                    $frame = \App\Frame::findOrFail((int)str_replace("f", "", $pos));
                    $frame->order = $ord;
                    $frame->save();
                }
            }

            $dataTypeContent = app($dataType->model_name)->findOrFail($id);
            //Here we should update image

            $mediaItems = $dataTypeContent->getMedia();
            if($request->input('dropimage') || !empty($request->file('uploadFile')))
                foreach ($mediaItems as $mediaItem) {
                    $mediaItem->delete();
                }

            if(is_array($request->file('uploadFile')))
                foreach ($request->file('uploadFile') as $key => $value) {
                    $imageName = time(). $key . '.' . $value->getClientOriginalExtension();
                    $value->move(public_path('storage/source'), $imageName);
                    $dataTypeContent->addMedia(public_path('storage/source')."/".$imageName)->toMediaCollection();
                }


            event(new BreadDataUpdated($dataType, $data));

            return redirect()
                ->route("voyager.{$dataType->slug}.edit", $id)
                ->with([
                    'message'    => __('voyager::generic.successfully_updated')." {$dataType->display_name_singular}",
                    'alert-type' => 'success',
                ]);

            /*
            return redirect()
                ->route("voyager.{$dataType->slug}.index")
                ->with([
                    'message'    => __('voyager::generic.successfully_updated')." {$dataType->display_name_singular}",
                    'alert-type' => 'success',
                ]);
            */
        }
    }

    public function deleteframe(Request $request, $id)
    {
        $slug = 'Frame';
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        $dataTypeContent = app($dataType->model_name)->findOrFail((int)$request->input('frame'));

        $this->authorize('delete', app($dataType->model_name));

        if($dataTypeContent->id_cardtemplate == $id)
            $dataTypeContent->delete();


        return redirect()
            ->route("voyager.cardtemplate.edit", $id)
            ->with([
                'message'    => __('voyager::generic.successfully_updated')." Cardtemplate",
                'alert-type' => 'success',
            ]);

    }


}