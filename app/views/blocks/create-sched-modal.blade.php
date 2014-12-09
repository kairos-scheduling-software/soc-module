<div class="modal" id="create-sched-modal">
  <div class="modal-header">
    <h2>Create a Schedule</h2>
  </div>
  <div class="modal-body">
    <div id="modal-errors"></div>
    <form method="POST" action="{{ URL::route('create-sched') }}" name="login_form" id="create-sched-form">
      <p><input type="text" class="form-control" name="sched_name" placeholder="Name your schedule..." required></p>
    </form>
    <div class="modal-buttons">
      <button class="btn btn-lg" id="create-schedule">Create</button>
      <button class="btn btn-lg" id="cancel-create" data-dismiss="modal">Cancel</button>
    </div>
  </div>
</div>