<?php namespace GestorImagenes\Http\Controllers;
use GestorImagenes\Album;
use GestorImagenes\Foto;
use Carbon\Carbon;
use GestorImagenes\Http\Requests\MostrarFotosRequest;
use GestorImagenes\Http\Requests\CrearFotoRequest;
use GestorImagenes\Http\Requests\ActualizarFotoRequest;
use GestorImagenes\Http\Requests\EliminarFotoRequest;
use Illuminate\Http\Request;//Este lo usamos para poder resivir el id del album

class FotoController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function getIndex(MostrarFotosRequest $request)
	{
        $id=$request->get('id');
        $album=Album::find($id);
        $fotos=$album->fotos;
        
		return view('fotos.mostrar',['fotos'=>$fotos,'id'=>$id]);
	}
    public function getCrearFoto(Request $request){
        
        $id = $request->get('id');
        
        return view('fotos.crear-foto',['id'=>$id]); 
    }
    public function postCrearFoto(CrearFotoRequest $request){
        
        $id=$request->get('id');
        $imagen=$request->file('imagen');
        $ruta='/img/';
        $nombre=sha1(Carbon::now()).".".$imagen->guessExtension();
        $imagen->move(getcwd().$ruta,$nombre);
        Foto::create(
        [
            'nombre'=>$request->get('nombre'),
            'descripcion'=>$request->get('descripcion'),
            'ruta'=>$ruta.$nombre,
            'album_id'=>$id,
        ]
        );
        
        return redirect("/validado/fotos?id=$id")->with('creada','La foto ha sido subida'); 
    }
    public function getActualizarFoto($id){
        $foto=Foto::find($id);
        return view('fotos.actualizar-foto', ['foto'=>$foto]); 
    }
    public function postActualizarFoto(ActualizarFotoRequest $request){
        $foto=Foto::find($request->get('id'));
        $foto->nombre=$request->get('nombre');
        $foto->descripcion=$request->get('descripcion');
        if($request->hasFile('imagen')){
            $imagen=$request->file('imagen');
            $ruta='/img/';
            $nombre=sha1(Carbon::now()).".".$imagen->guessExtension();
            $imagen->move(getcwd().$ruta,$nombre);
            $rutaanterior=getcwd().$foto->ruta;
            if(file_exists($rutaanterior)){
                unlink(realpath($rutaanterior));
            }
            $foto->ruta=$ruta.$nombre;
        }
        $foto->save();
        return redirect("/validado/fotos?id=$foto->album_id")->with('editada','La foto fue editada correctamente'); 
    }
    public function postEliminarFoto(EliminarFotoRequest $request){
        $foto=Foto::find($request->get('id'));
        $rutaanterior=getcwd().$foto->ruta;
        
        if(file_exists($rutaanterior)){
            unlink(realpath($rutaanterior));
        }
        $foto->delete();
        
        return redirect("/validado/fotos?id=$foto->album_id")->with('eliminada','La foto ha sido eliminada correctamente'); 
    }
    public function missingMethod($parameters=array()){
        abort(404);
    }
}