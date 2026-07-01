@extends('header')
@section('content')
<div class="container mt-5">
  @if (Auth::user()->type != 'user')
  <a  type = 'button' class = 'btn btn-sm btn-primary' href="{{route('invite')}}">Invite</a>
  @endif
 
</div>
<div class="container mt-5">
  <table id="myTable" class="display">
    <thead>
        <tr>
            <th>Client Name</th>
            <th>Users</th>
            <th>Total Generated URLs</th>
            
        </tr>
    </thead>
    <tbody>
      @foreach ($details as $value)
      <tr>
        <td>{{$value->company}}</td>
        <td>{{$value->users}}</td>
        <td>{{$value->url}}</td>
        
    </tr>
      @endforeach
       
    
    </tbody>
    
</table>
</div>

{{-- URLTABLE  --}}

<div class="container mt-5">
 @if (auth()->user()->type != 'superAdmin')
 <button class="btn btn-sm btn-primary genUrl">Generate</button>
 @endif
 
  <table id="urlTable" class="display">
    <thead>
        <tr>
            <th>Short URl</th>
            <th>Long URl</th>
            <th>Hits</th>
            <th>Company</th>
            <th>Created On</th>

        </tr>
    </thead>
    <tbody>
      @foreach ($urlDetials as $value)
      <tr>
        <td>
          <span class="copyable-field"> 
            {{ $value->m_url }}
            <i class="fa fa-copy"></i>
          </span>
         
         </td>
        <td>{{$value->org_url}}</td>
        <td>{{$value->hits}}</td>
        <td>{{$value->company}}</td>
        <td>{{date('d-M-Y',strtotime($value->created_at))}}</td>
    </tr>
      @endforeach
       
    
    </tbody>
    
</table>
{{-- Generate URL Model --}}
<!-- Modal -->

<div class="modal fade" id="genUrlModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Generate URL</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
        <form id = "genUrlForm">
          <meta name="csrf-token" content="{{ csrf_token() }}">
          <label>URL</label>
          <input type = "text" class="form-control" name = "org_url"><br/>
          <button class = "btn btn-sm btn-primary" type= "submit">submit</button>
        </form>
      </div>
      </div>
     
    </div>
  </div>
</div>
</div>
@endsection

@section('script')
  <script>
    $(document).on('click', '.copyable-field', function () {
    // Get text inside the cell
    var Copy = $(this).text();
    
    var textToCopy = '{{route("shortUrl")}}/'+Copy;
    // Use Navigator API to save to user clipboard
    navigator.clipboard.writeText(textToCopy).then(function() {
        // Optional alert or toast notification 
        alert("Copied");
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
    });
});
  </script>
<script>
  $(document).ready(function(){
    $('#myTable').DataTable();
    $('#urlTable').DataTable();

  });

  $(document).ready(function(){
    $('.genUrl').click(function(){
        $("#genUrlModal").modal('toggle');
    });
  });

  $(document).ready(function(){
      $('#genUrlForm').on('submit', function (e) {
      e.preventDefault();
      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
      type:'POST',
      url:'{{route("addURL")}}',
      data:$(this).serialize(),
      dataType:'JSON',
      success:function(data) {
         if(data.status == true){
             alert("Insert Url successfully");
         }else{
          alert("Something is went wrong");
           
         }
         $("#genUrlModal").modal('toggle');

         location.reload(); 

      },
      error: function (msg) {
        
   }
});
    });
  })
</script>
@endsection
