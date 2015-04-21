<?php

class ResourceController extends BaseController 
{
	public function load_room_manager() {
		return View::make('room-manager')->with([
			'page_name'	=>	'Room Manager'
		]);
	}
	
	public function load_prof_manager() {
		// TODO: Replace with real code
		return View::make('room-manager')->with([
			'page_name'	=>	'Room Manager'
		]);
	}
	
	public function get_rooms() {
		try {
			$grp_id = Input::get('grp', null);
			if ($grp_id == null || $grp_id == '-1') {
				$rooms = Room::select('id', 'name', 'capacity')->get();
			} else {
				$rooms = RoomGroup::find($grp_id)->rooms;
			}
			return json_encode($rooms);
		} catch (Exception $e) {
			//return json_encode($e);
			return Response::json(['error' => 'Could not get rooms list for group ' + $grp_id], 500);
		}
	}
	
	public function get_room_groups() {
		$grps = RoomGroup::select('id', 'name', 'description')->get();
		
		return json_encode($grps);
	}
	
	public function add_room() {
		$room = new Room;
		$room->name = Input::get('name');
		$room->capacity = Input::get('capacity', null);
		if ($room->capacity == '') {
			$room->capacity = null;
		}
		
		$room->save();
	}
	
	public function add_room_group() {
		try {
			$grp = new RoomGroup;
			$grp->name = Input::get('value');
			$grp->description = Input::get('description', '');
			
			$grp->save();
		} catch (Exception $e) {
			return json_encode($e);
			//return Response::json(['error' => 'Could not create new room group'], 500);
		}
	}
	
	public function edit_room() {
		$id = Input::get('pk');
		$field = Input::get('name');
		$value = Input::get('value');
		
		$result = new StdClass;
		
		try {
			$room = Room::find($id);
			try {
				if ($field == 'name') {
					$room->name = $value;
					$room->save();
				} elseif ($field == 'capacity') {
					$room->capacity = $value;
					$room->save();
				} else {
					$result->error = 'Invalid field name ' + $field;
				}
			} catch (Exception $e) {
				$result->error = 'Could not update field value ' + $value;
			}
		} catch (Exception $e) {
			$result->error = 'Could not find room ' + $id;
		}
		
		return json_encode($result);
	}
	
	public function edit_room_group() {
		
	}
	
	public function get_professors() {}
	
	public function add_professor() {}
	
	public function remove_professor() {}
}
