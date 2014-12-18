<div id="class-listing-{{ $class->id }}" class="data-list-row">
	<div class="class-listing-name">
	{{ $class->name }} 
	</div>
	<div class="class-listing-actions">
		<button class="btn btn-sm edit-class" 
				data-id="{{ $class->id }}"
				data-url="{{ URL::route('edit-class', array($schedule->id, $class->id)) }}"
				data-edit="#edit-class-{{ $class->id }}">
			{{ FA::icon('edit') }}
			EDIT
		</button>
		<button class="btn btn-sm remove-class" 
				data-id="{{ $class->id }}"
				data-url="{{ URL::route('delete-class', array($schedule->id, $class->id)) }}">
			{{ FA::icon('times') }}
			REMOVE
		</button>
	</div><br>
</div>
<div id="edit-class-{{ $class->id }}" class="edit-class-row data-list-row">
	<form 	action="{{ URL::route('edit-class', array($schedule->id, $class->id)) }}" 
			class="edit-class-form"
			method="POST">
		<div class="class-listing-name">
			<input 	id="edit-name-{{ $class->id }}" 
					type="text" 
					name="name" 
					class="form-control" 
					value="{{ $class->name }}"/>
		</div>
		<div class="edit-class-actions">
			<button class="btn btn-sm btn-save">
				{{ FA::icon('save') }}&nbsp;SAVE
			</button>
			<button class="btn btn-sm btn-cancel" 
					data-row="#class-listing-{{ $class->id }}"
					data-input="#edit-name-{{ $class->id }}"
					data-name="{{ $class->name }}">
				{{ FA::icon('times') }}&nbsp;CANCEL
			</button>
		</div>
	</form>
</div>
<hr>