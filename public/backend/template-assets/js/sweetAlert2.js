$(document).on('click', '.row-delete', function(e){
    e.preventDefault();
    let link = $(this).attr('href');
      Swal.fire({
      title: 'Delete',
      text: 'Are you sure want to delete.?', 
      icon: 'question',
      confirmButtonText: 'Yes',
      denyButtonText: "No", 
      confirmButtonColor:'#198754' ,
      showDenyButton: true,
      cancelButtonColor: '#265C39',
      }).then((result)=> {
        if(result.isConfirmed){
          window.location.href = link;
        } else if(result.isDenied){
          // anything
        }
      });
  });