<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Documents;
use Ramsey\Uuid\Uuid;

class DocumentsController extends Controller
{
    /**
     * получить список документов с пагинацией
     *
     * @return App\Documents
     */
    public function index(DocumentRequest $request)
    {
		$perPage = $request->input('perPage');

        $documents = Documents::select()
           ->orderBy('id', 'desc')
           ->paginate($perPage);
		
		return [
			'document'=>$documents->items(),
			'pagination' => [
				'page' => $documents->currentPage(),
				'perPage' => $documents->perPage(),
				'total' => $documents->total()
			]
		];
		
    }

    /**
     * Создаем черновик документа
     *
	 * @param  App\Http\Requests\DocumentRequest $request
     * @return App\Documents
     */
    public function draft(DocumentRequest $request)
    {
		
        if ($request->isMethod('post')) {
			$document = new Documents; 
			$document->uuid = Uuid::uuid4(); 
			$document->status = 'draft'; 
			$document->payload = new \stdClass ; 
			$document->save(); 
			
			return ['document'=>$document];
		}
		
		
    }

    /**
     * редактировать документ
     *
     * @param  App\Http\Requests\DocumentRequest  $request
     * @param  UUID  $id
     * @return App\Documents
     */
    public function patch(DocumentRequest $request, $id)
    {
		// Если валидация не прошла то отдаем стутус 400
		if (isset($request->validator) && $request->validator->fails()) {
			return response()->json($request->validator->messages(), 400);
		}
	
        $document = Documents::where('uuid', $id)->firstOrFail();
		
		if($document->status == "draft") {
			$document->payload = $request->payload; 
			$document->save();
		}
		else {
			return response()->json("Record already published", 400);
		}
		
		return ['document'=>$document];
    }

    /**
     * // получить документ по id
     *
     * @param  uuid  $id
     * @return App\Documents
     */
    public function show($id)
    {
		$document = Documents::where('uuid', $id)->firstOrFail();
		return ['document'=>$document];
    }

    /**
     * опубликовать документ
     *
	 * @param  App\Http\Requests\DocumentRequest $request
     * @param  uuid  $id
     * @return \Illuminate\Http\Response
     */
    public function publish(DocumentRequest $request, $id)
    {
        $document = Documents::where('uuid', $id)->firstOrFail();
		if($document->status == "draft") {
			$document->status = "published";
			$document->save();
		}
		
		return ['document'=>$document];
    }

}
