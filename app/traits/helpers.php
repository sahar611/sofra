<?php 


 function resposeJson($status,$message,$data=null){
   $response=[
            'status'=>$status ,
            'message'=>$message,
            'data'=>$data
   ];
            return response()->json($response);

}

function settings()
{
  $settings=\App\Models\Settings::find(1);
    return  $settings;
}
 function getPagination($collection)
{
   $paginate=  [
        "per_page" => $collection->perPage(),
        "path" => $collection->path(),
        "total" => $collection->total(),
        "current_page" => $collection->currentPage(),
        "last_page" => $collection->lastPage(),

    ];
    return $paginate;
}

?>