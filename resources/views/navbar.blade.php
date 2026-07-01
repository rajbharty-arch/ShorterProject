<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">

      <a class="navbar-brand" href="{{ url('/') }}">
          URL Shortener
      </a>
      
      <div class="collapse navbar-collapse">
        @auth

          <ul class="navbar-nav ms-auto">
              <li class="nav-item">
                  <form action="{{ route('logout') }}" method="POST">
                      @csrf
                      <button type="submit" class="btn btn-danger">
                          Logout
                      </button>
                  </form>
              </li>
          </ul>
          @endauth
        

      </div>
      @auth
          <h3 style = "color:darkgoldenrod">{{Auth::user()->type}}</h3>
      @endauth
  </div>
</nav>