<?php

namespace App\Http\Controllers;

use App\Deno;
use App\Item;
use App\Strength;
use App\StrengthToItem;
use Illuminate\Http\Request;
use App\EventManagement;
use Session;
use View;
use Input;
use PDF;
use Excel;
use Carbon\Carbon;

use functions\OwnLibrary;


class StrengthController extends Controller
{

	private $moduleId = 38;
	private $tableAlies;

	public function index() {
		OwnLibrary::validateAccess($this->moduleId,1);

		$from = Input::get('from');
		$todate = Input::get('todate');

		$strengths = Strength::with('strengthToItems')->orderBy('strength.id','desc');

		if (!empty($from)){
			$strengths = $strengths->where('created_at','>=',date("Y-m-d", strtotime( $from)));
		}

		if (!empty($todate)){
			$strengths = $strengths->where('created_at','<=',date("Y-m-d", strtotime($todate)));
		}

		$strengths = $strengths->paginate(20);

		return View::make('strength.index',compact('strengths'));
	}

	public function create() {
		OwnLibrary::validateAccess($this->moduleId,2);
		$items = Item::orderBy('item_name')->pluck('item_name','id');
		return View::make('strength.create',compact('items'));
	}

	public function details($id) {
		OwnLibrary::validateAccess($this->moduleId,15);
		$strengthToItems = StrengthToItem::where('strength_id','=',$id)->get();
		return View::make('strength.details',compact('strengthToItems'));
	}

	public function store(Request $request) {
		OwnLibrary::validateAccess($this->moduleId,2);

		$strength = new Strength();

		if ($strength->save()){
			$strengthSum = 0;
			for($m=0; count($request->item_id)>$m; $m++){
				$strengthToItems = new StrengthToItem();

				$item = Item::find($request->item_id[$m]);

				$strengthToItems->strength_id = $strength->id;
				$strengthToItems->bsd_items_id = $request->item_id[$m];
				$strengthToItems->person = !empty($request->person[$m]) ? $request->person[$m] : 1;
				$strengthToItems->days = !empty($request->days[$m]) ? $request->days[$m] :1;
				$strengthToItems->strength = $item->strength;
				$strengthToItems->total = $item->strength * $request->days[$m] * $request->person[$m];
				$strengthToItems->deno_id = $item->item_deno;
				$strengthToItems->save();
			}
				session()->flash('success','Data successfully inserted');
				return redirect('/strength-calculation');

		}else{
			session()->flash('error','Something Went Wrong!!!, Please Try Again');
			return redirect()->back();
		}
	}

	public function edit($id) {
		OwnLibrary::validateAccess($this->moduleId,2);
		$items = Item::orderBy('item_name')->pluck('item_name','id');
		$strengths = StrengthToItem::where('strength_id',$id)->get();

		return View::make('strength.edit',compact('items','strengths','id'));
	}

	public function update(Request $request){
		OwnLibrary::validateAccess($this->moduleId,3);

		$id = $request->id;

		$strength = Strength::find($id);

		$strength->total = 1;
		$strength->updated_at = Carbon::now()->toDateTimeString();

		if ($strength->save()){

			StrengthToItem::where('strength_id',$id)->delete();

			for($m=0; count($request->item_id)>$m; $m++){
					$strengthToItems = new StrengthToItem();

					$item = Item::find($request->item_id[$m]);

					$strengthToItems->strength_id = $id;
					$strengthToItems->bsd_items_id = $request->item_id[$m];
					$strengthToItems->person = !empty($request->person[$m]) ? $request->person[$m] : 1;
					$strengthToItems->days = !empty($request->days[$m]) ? $request->days[$m] :1;
					$strengthToItems->strength = $item->strength;
					$strengthToItems->total = $item->strength * $request->days[$m] * $request->person[$m];
					$strengthToItems->deno_id = $item->item_deno;
					$strengthToItems->save();
				}
			session()->flash('success','Data successfully updated');
			return redirect('/strength-calculation');

		}else{
			session()->flash('error','Something Went Wrong!!!, Please Try Again');
			return redirect()->back();
		}
	}

	public function destroy($id){
		OwnLibrary::validateAccess($this->moduleId,4);
		if (Strength::destroy($id)){
			StrengthToItem::where('strength_id',$id)->delete();
			session()->flash('success','Successfully deleted');
			return redirect()->back();
		}else{
			session()->flash('error','Data not deleted!!!');
			return redirect()->back();
		}
	}

	public function itemDestroy($id){
		OwnLibrary::validateAccess($this->moduleId,4);
		if (StrengthToItem::destroy($id)){
			session()->flash('success','Successfully deleted');
			return redirect()->back();
		}else{
			session()->flash('error','Data not deleted!!!');
			return redirect()->back();
		}
	}

	public function printPdf($id) {
		OwnLibrary::validateAccess($this->moduleId,9);

		$strengthToItems = StrengthToItem::where('strength_id','=',$id)->get();

		$data = [
			'strengthToItems' => $strengthToItems,
			'id' => $id
		];

		$pdf= PDF::loadView('strength.print',$data,[],['format' => [215.9, 342.9]]);
		return $pdf->stream('Strength-Calculation-'.date('d-m-Y').'.pdf');
	}

	public function printExcel($id) {
		OwnLibrary::validateAccess($this->moduleId,9);

		$strengthToItems = StrengthToItem::where('strength_id','=',$id)->get();


		Excel::create('Strength-Calculation-'.date('d-m-Y'), function($excel) use ($strengthToItems , $id) {
			$excel->sheet('Excel sheet', function($sheet) use ($strengthToItems , $id) {
				$sheet->loadView('strength.print')->with(compact('strengthToItems','id'));
				$sheet->setOrientation('landscape');
			});
		})->export('xlsx');
	}

	public function strength(Request $request){
		$items = Item::find($request->id);

		$deno = Deno::find($items->item_deno);

		$data = [
			'strength' => $items->strength,
			'deno' => $deno->name
		];

		return json_encode($data);
	}

}
