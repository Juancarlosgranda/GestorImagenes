<?php namespace GestorImagenes\Http\Requests;
use Illuminate\Support\Facades\Auth;
use GestorImagenes\Album;
use GestorImagenes\Http\Requests\Request;

class CrearFotoRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
        $user = Auth::user();
        $id=$this->get('id');
        $album=$user->albumes()->find($id);
        if($album){
		return true;
	   }
    }

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'id'=>'required|exists:albumes,id',
            'nombre'=>'required',
            'descripcion'=>'required',
            'imagen'=>'required|image|max:20000'
		];
	}

}
