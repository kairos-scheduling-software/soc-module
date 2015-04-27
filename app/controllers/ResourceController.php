<?php

class ResourceController extends BaseController 
{
	public function load_room_manager() {
		$user = Auth::user();
		if (!$user->is_admin) {
			return Redirect::route('dashboard');
		}
		return View::make('room-manager')->with([
			'page_name'	=>	'Room Manager'
		]);
	}
	
	public function load_prof_manager() {
		$user = Auth::user();
		if (!$user->is_admin) {
			return Redirect::route('dashboard');
		}
		return View::make('prof-manager')->with([
			'page_name'	=>	'Professor Manager'
		]);
	}
	
	public function get_rooms() {
		try {
			//{'grp': 2, 'fields_mapping': {'id': 'value', 'name': 'text'}}
			$grp_id = Input::get('grp', null);
			$fields_mapping = Input::get('fields_mapping', null);
			
			if ($grp_id == null || $grp_id == '-1') {
				$rooms = Room::orderBy('name', 'asc')->select('id', 'name', 'capacity')->get();
			} else {
				$rooms = RoomGroup::find($grp_id)->rooms->sortBy('name');
			}
			
			if ($fields_mapping == null) {
				return json_encode($rooms);
			} else {
				foreach ($fields_mapping as $key => $value) {
					if ($key != 'id' && $key != 'name' && $key != 'capacity') {
						return Response::json(['error' => 'Invalid field name ' . $key], 500);
					}
				}
				$return_arr = [];
				foreach ($rooms as $rm) {
					$obj = new StdClass;
					foreach($fields_mapping as $key => $value) {
						$obj->$value = $rm->$key;
					}
					$return_arr[] = $obj;
				}
				return json_encode($return_arr);
			}
		} catch (Exception $e) {
			//return Response::json($e, 500);
			return Response::json(['error' => 'Could not get rooms list for group ' . $grp_id], 500);
		}
	}
	
	public function get_room_groups() {
		$grps = RoomGroup::select('id', 'name', 'description')->get();
		
		return json_encode($grps);
	}
	
	public function add_room() {
		try {
			$room = new Room;
			$room->name = Input::get('name');
			$room->capacity = Input::get('capacity', null);
			if ($room->capacity == '') {
				$room->capacity = null;
			}
			
			$room->save();
		} catch (Exception $e) {
			return Response::json(['error' => 'Could not create new room'], 500);
		}
	}
	
	public function add_room_group() {
		try {
			$grp = new RoomGroup;
			$grp->name = Input::get('value');
			$grp->description = Input::get('description', '');
			
			$grp->save();
		} catch (Exception $e) {
			//return json_encode($e);
			return Response::json(['error' => 'Could not create new room group'], 500);
		}
	}
	
	public function edit_room() {
		$id = Input::get('pk');
		$field = Input::get('name');
		$value = Input::get('value');
		
		try {
			$room = Room::find($id);
			try {
				if ($field == 'name') {
					$room->name = $value;
					$room->save();
				} elseif ($field == 'capacity') {
					$room->capacity = $value;
					$room->save();
				} elseif ($field == 'remove') {
					if ($value[0] == '1') {
						$room->delete();
					}
				} else {
					return Response::json(['error' => 'Invalid field ' . $field], 500);
				}
			} catch (Exception $e) {
				return Response::json(['error' => 'Could not update field ' . $field], 500);
			}
		} catch (Exception $e) {
			return Response::json(['error' => 'Could not find room ' . $id], 500);
		}
	}
	
	public function edit_room_group() {
		$grp_id = Input::get('pk');
		$field = Input::get('name');
		$vals = Input::get('value');
		
		if ($field == 'remove') {
			try {
				if ($vals[0] == '1') {
					$room_group = RoomGroup::find($grp_id);
					$room_group->delete();
				}
			} catch (Exception $e) {
				return Response::json(['error' => 'Could not delete room group'], 500);
			}
		} elseif ($field == 'rooms') {
			try {
				$room_group = RoomGroup::find($grp_id);
				$room_group->rooms()->sync($vals);
			} catch (Exception $e) {
				return Response::json(['error' => 'Could not update rooms list'], 500);
			}
		} else {
			return Response::json(['error' => 'Invalid field '. $field], 500);
		}
	}
	
	public function get_professors() {
		try {
			$profs = Professor::orderBy('name', 'asc')->select('id', 'name', 'uid')->get();
			return json_encode($profs);
		} catch (Exception $e) {
			return Response::json(['error' => 'Could not get professor list'], 500);
		}
	}
	
	public function add_professor() {
		try {
			$prof = new Professor;
			$prof->name = Input::get('name');
			$prof->uid = Input::get('uid', '');
			
			$prof->save();
		} catch (Exception $e) {
			return Response::json(['error' => 'Could not save new professor'], 500);
		}
	}
	
	public function edit_professor() {
		$id = Input::get('pk');
		$field = Input::get('name');
		$value = Input::get('value');
		
		try {
			$prof = Professor::find($id);
			try {
				if ($field == 'name') {
					$prof->name = $value;
					$prof->save();
				} elseif ($field == 'uid') {
					$prof->uid = $value;
					$prof->save();
				} elseif ($field == 'remove') {
					if ($value[0] == '1') {
						$prof->delete();
					}
				} else {
					return Response::json(['error' => 'Invalid field ' . $field], 500);
				}
			} catch (Exception $e) {
				return Response::json(['error' => 'Could not update field ' . $field], 500);
			}
		} catch (Exception $e) {
			return Response::json(['error' => 'Could not find professor ' . $id], 500);
		}
	}
}
