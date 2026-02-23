$(document).ready(function () {
  $('#sidebarCollapse').on('click', function () {
    $('#sidebar').toggleClass('active');
    $('#content').toggleClass('active');
    $('.top-navbar').toggleClass('active');
    $('.main-content').toggleClass('active');
  });

  $('.more-button,.body-overlay').on('click', function () {
    $('#sidebar, .body-overlay').toggleClass('show-nav');
  });




});




//To open sidebar collapse menu & close others...s
function sidebarCollapseMenu(id) {
  let startNum = 1;
  let endNum = 30;
  while (startNum <= endNum) {
    if (id == startNum) {
      $('#sidebar_collapse_' + startNum).slideToggle(500);
    } else {
      $('#sidebar_collapse_' + startNum).slideUp(500);
    }

    startNum++;
  }
}


// sidebar collapse menu hidden



$('#content').on('click', function(){
  if($('#sidebar.active ul li>ul').is(':visible')){
    $('#sidebar.active ul li>ul').slideUp();
  }
})






// img preview

function PreviewImage(selectFile, previewImg) {
  var oFReader = new FileReader();
  oFReader.readAsDataURL(document.getElementById(selectFile).files[0]);

  oFReader.onload = function (oFREvent) {
    document.getElementById(previewImg).src = oFREvent.target.result;
  };
}

function cancelPreview(selectFile, previewImg) {
  const img = document.getElementById(previewImg);
  img.src = window.location.origin + "/backend/template-assets/images/img_preview.png";
  $("#" + selectFile).val('');
}

function slideToggleControl(id){
  $(document).ready(function () {
    $(`#${id}`).slideToggle()
  })
}


// add_post_page input field validate 


function validationInput(value,id){
  const elText = document.getElementById(id);
      if(!value){
          elText.classList.add('d-block')
          elText.classList.remove('d-none')
      }else{
          elText.classList.remove('d-block');
          elText.classList.add('d-none')
      }
  }


// select2 js 

$(".js-example-tags").select2({ tags: true })

// leads page 

$(document).ready(function(){
  $('.leads_page_select_option').select2({
    dropdownParent:'#edittext'
  });
});

$(document).ready(function(){
  $('#oneInputModalCenter').select2({
    dropdownParent:'#edittext'
  });
});



function slideToggleControl(id){
  $(document).ready(function () {
    $(`#${id}`).slideToggle()
  })
}



// dashboard page 

// toggle 

function toggleCard(id){
  $('#'+id).slideToggle();
}

// console.clear();

// add item in invoice page start 
function renderInvoiceItem(){
  $('.row.invoice #item_render').append(`
  
  <div class="p-4 mb-3 invoice-add-item">
    <div class="d-md-flex d-none row pe-5">
        <p class="col-6">Item</p>
        <p class="col-2">Cost</p>
        <p class="col-2">Qty</p>
        <p class="col-2">Price</p>
    </div>
   <div class="row border p-2 rounded ">
    <div style="width:90%" class="border-end w-90">
        <div class="row">
            <div class="col-md-6 mb-2">
                <label for="selectItem">Select Item</label>
                <select class="form-control js-example-tags " name="selectItem" id="selectItem">
                    <option value="template">ABC Template</option>
                    <option value="template">ABC Template</option>
                    <option value="template">ABC Template</option>
                    <option value="template">ABC Template</option>
                    <option value="template">ABC Template</option>
                </select>

                <div class="mt-2">
                    <label for="description">Description</label>
                <textarea  placeholder="Description" class="w-100 p-2 form-control" name="description" id="description" cols="15" rows="5"></textarea>
                </div>
            </div>

            <div class="col-md-2 mb-2">
                <label for="number_Cost">Cost</label>
                <input class="form-control" value="0" type="number" name="number_Cost" id="number_Cost">
            </div>
            <div class="col-md-2 mb-2">
                <label for="Qty">Qty</label>
                <input class="form-control" value="1" type="number" name="Qty" id="Qty">
            </div>

            <div class="col-md-2 mb-2">
                <p>Price</p>
                <p>$0</p>
            </div>

        </div>
    </div>
    <div class="col d-flex flex-column justify-content-between align-items-end pb-2">
        <span class="material-symbols-outlined me-0 removeInvoiceItem" role="button" >
            close
            </span>
            <span class="material-symbols-outlined me-0 pointer">
                settings
                </span>
    </div>
   </div>
</div>

  `);

    }

$(document).on('click','.removeInvoiceItem', function(){
  $(this).parents('.invoice-add-item').remove();
})

// add item in invoice page end 

