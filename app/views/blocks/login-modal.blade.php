<div class="modal" id="login-modal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h2>Login to Kairos</h2>
  </div>
  <div class="modal-body">
    <form method="post" action="{{ URL::route('postLogin') }}" name="login_form">
      <p><input type="text" class="form-control" name="username" id="email" placeholder="Username"></p>
      <p><input type="password" class="form-control" name="password" placeholder="Password"></p>
      <p><button type="submit" class="btn btn-login">Log in</button>
        <a href="#" class="login-link">Forgot Password?</a>
      </p>
    </form>
  </div>
  <div class="modal-footer login-footer">
    New To Kairos?
    <a href="#" id="btn-register" class="btn">Register</a>
  </div>
</div>