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
	
	public function load_time_manager() {
		// TODO: Replace with real code
		$user = Auth::user();
		if (!$user->is_admin) {
			return Redirect::route('dashboard');
		}
		return View::make('time-manager')->with([
			'page_name'	=>	'Time Manager'
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
	
	
	// Time manager
	public function import_times() {
		$file = Input::file('times_file');
		$data = file_get_contents($file->getRealPath());
		
		//return $data;
		
		$time_data = json_decode($data);
		
		//return json_encode($time_data);
		
		DB::beginTransaction();
		
		//$existing_times = Etime::where('standard_block', true)->get();
		try {
			Etime::where('standard_block', '=', true)->update(array('standard_block' => false));
			
			DB::table('time_groups')->delete();
			
			foreach($time_data as $obj) {
				foreach($obj->days as $day) {
					$days_str = $this->parse_days($day);
					$days_count = strlen($day);
					foreach($obj->duration as $duration) {
						$timeList = [];
						foreach($obj->startTime as $start_tm) {
							$tm = Etime::firstOrCreate(array(
								'starttm' => $start_tm,
								'length' => $duration,
								'days' => $days_str
							));
							$tm->standard_block = true;
							$tm->save();
							$timeList[] = $tm;
						}
						$grp_name = $duration . 'min - ' . $days_count . 'days';
						$tm_grp = TimeGroup::firstOrCreate(array('name' => $grp_name));
						$tm_grp->etimes()->saveMany($timeList);
					}
				}
			}
		} catch (Exception $e) {
			DB::rollback();
			$result = new StdClass;
			$result->error = 'Could not import time blocks';
			$result->message = $e->getMessage();
			return Response::json($result, 500);
		}
		DB::commit();
	}
	
	public function get_times() {
		try {
			//{'grp': 2, 'fields_mapping': {'id': 'value', 'name': 'text'}}
			$grp_id = Input::get('grp', null);
			//$fields_mapping = Input::get('fields_mapping', null);
			$isEditableList = Input::get('editableList', false);
			
			if ($grp_id == null || $grp_id == '-1') {
				$times = Etime::where('standard_block', '=', true)->orderBy('starttm', 'asc')->get();
			} else {
				$times = TimeGroup::find($grp_id)->etimes->sortBy('starttm');
			}
			
			if (!$isEditableList) {
				return json_encode($times);
			} else {
				$return_arr = [];
				foreach ($times as $tm) {
					$obj = new StdClass;
					$days = [];
					foreach (explode('|', $tm->days) as $day) {
						switch ($day) {
							case 1:
								$days .= 'M';
								break;
							case 2:
								$days .= 'T';
								break;
							case 3:
								$days .= 'W';
								break;
							case 4:
								$days .= 'H';
								break;
							case 5:
								$days .= 'F';
								break;
							case 6:
								$days .= 'S';
								break;
							case 7:
								$days .= 'U';
								break;
						}
					}
					$obj->text = $tm->length . 'min - ' . $tm->starttm . ' - ' . $days;
					$obj->value = $tm->id;
					$return_arr[] = $obj;
				}
				return json_encode($return_arr);
			}
		} catch (Exception $e) {
			//return Response::json($e, 500);
			return Response::json(['error' => 'Could not get time list for group ' . $grp_id], 500);
		}
	}
	
	public function get_time_groups() {
		$grps = TimeGroup::select('id', 'name')->get();
		
		return json_encode($grps);
	}
	
	public function add_time() {
		try {
			$time = new Time;
			$time->starttm = Input::get('start');
			$time->length = Input::get('length');
			$time->days = Input::get('days');
			$time->standard_block = Input::get('standard_block', true);
			
			$time->save();
		} catch (Exception $e) {
			return Response::json(['error' => 'Could not create new time block'], 500);
		}
	}
	
	public function add_time_group() {
		try {
			$grp = new TimeGroup;
			$grp->name = Input::get('value');
			
			$grp->save();
		} catch (Exception $e) {
			//return json_encode($e);
			return Response::json(['error' => 'Could not create new time group'], 500);
		}
	}
	
	public function archive_time() {
		$id = Input::get('pk');
		$field = Input::get('name');
		$val = Input::get('value');
		
		if ($field != 'remove' || $val[0] != '1') return;
		
		$time = Time::find($id);
		
		if ($time == null) {
			return Response::json(['error' => 'Could not find time block ' . $id], 500);
		}
		
		try {
			$time->standard_block = false;
			$time->save();
		} catch (Exception $e) {
			return Response::json(['error' => 'Could not delete time block ' . $id], 500);
		}
	}
	
	public function edit_time_group() {
		$grp_id = Input::get('pk');
		$field = Input::get('name');
		$vals = Input::get('value');
		
		if ($field == 'remove') {
			try {
				if ($vals[0] == '1') {
					$time_group = TimeGroup::find($grp_id);
					$time_group->delete();
				}
			} catch (Exception $e) {
				return Response::json(['error' => 'Could not delete time group'], 500);
			}
		} elseif ($field == 'times') {
			try {
				$time_group = TimeGroup::find($grp_id);
				$time_group->etimes()->sync($vals);
			} catch (Exception $e) {
				return Response::json(['error' => 'Could not update time list'], 500);
			}
		} else {
			return Response::json(['error' => 'Invalid field '. $field], 500);
		}
	}
	
	private function parse_days($days) {
		$days_arr = [];
		if (strpos($days, 'M') !== false) $days_arr[] = 1;
		if (strpos($days, 'T') !== false) $days_arr[] = 2;
		if (strpos($days, 'W') !== false) $days_arr[] = 3;
		if (strpos($days, 'H') !== false) $days_arr[] = 4;
		if (strpos($days, 'F') !== false) $days_arr[] = 5;
		
		return implode('|', $days_arr);
	}
}
