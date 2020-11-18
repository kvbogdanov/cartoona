<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Cardtemplate;
use App\Frame;

use TCG\Voyager\Database\Schema\SchemaManager;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Events\BreadDataDeleted;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Events\BreadImagesDeleted;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\Traits\BreadRelationshipParser;

use Validator;

class FrameController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    public function registerMediaCollections()
    {
        $this->addMediaCollection('audio');
    }

	public function createnew(Request $request, $slug, $id)
	{
        //$slug = $this->getSlug($request); // Cardtemplate
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        // Check permission
        $this->authorize('add', app($dataType->model_name));

        // сразу создаём фрейм, чтобы потом к нему без проблем привязать картинки (вроде бы так)
        // и ставим фрейм последним

        $order = DB::table('db_frame')
                ->where('id_cardtemplate', $id)
                ->max('order') + 1;

        $dataTypeContent = new Frame;
        $dataTypeContent->id_cardtemplate = $id;
        $dataTypeContent->order = $order;
        $dataTypeContent->save();

		$view = 'voyager::bread.create';
        if (view()->exists("voyager::$slug.create")) {
            $view = "voyager::$slug.create";
        }

		return Voyager::view($view, compact('dataType', 'dataTypeContent'));
	}

    public function edit(Request $request, $id)
    {
        $slug = $this->getSlug($request); // Frame
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        // Check permission
        $this->authorize('add', app($dataType->model_name));

        $dataTypeContent = app($dataType->model_name)->findOrFail($id);

        $view = 'voyager::bread.create';
        if (view()->exists("voyager::$slug.create")) {
            $view = "voyager::$slug.create";
        }

        return Voyager::view($view, compact('dataType', 'dataTypeContent'));
    }


    public function update(Request $request, $id)
    {
        $slug = 'Frame';

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Compatibility with Model binding.
        $id = $id instanceof Model ? $id->{$id->getKeyName()} : $id;
        $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);

        // Check permission
        $this->authorize('edit', $data);

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->editRows, $dataType->name, $id);
        if ($val->fails()) {
            return response()->json(['errors' => $val->messages()]);
        }

        $validator = Validator::make($request->all(), Frame::$rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->messages()]);
        }


        if (!$request->ajax()) {
            $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

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

            $mediaItems = $dataTypeContent->getMedia('audio');
            foreach ($mediaItems as $key => $media)
            {
                if (!isset($_POST['audio_ids']) || !in_array($media->id, $_POST['audio_ids']))
                    $media->delete();
            }

            if (is_array($request->file('uploadAudio')))
                foreach ($request->file('uploadAudio') as $key => $value) {
                    $fileName = time().rand(0,99999).'.'.$value->getClientOriginalExtension();
                    $value->move(public_path('storage/source'), $fileName);
                    $dataTypeContent->addMedia(public_path('storage/source')."/".$fileName)->toMediaCollection('audio');
                }

            event(new BreadDataUpdated($dataType, $data));

            return redirect()
                ->route("voyager.cardtemplate.edit", $dataTypeContent->id_cardtemplate)
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

}