<div class="modal" id="create-sched-modal">
  <div class="modal-header">
    <h2>Create a Schedule</h2>
  </div>
  <div class="modal-body">
    <form method="POST" action="{{ URL::route('create-sched') }}" name="login_form">
      <p><input type="text" class="form-control" name="sched_name" placeholder="Name your schedule..."></p>
    </form>
    <div class="modal-buttons">
      <button class="btn btn-lg">Create</button>
      <button class="btn btn-lg" data-dismiss="modal">Cancel</button>
    </div>
  </div>
</div>