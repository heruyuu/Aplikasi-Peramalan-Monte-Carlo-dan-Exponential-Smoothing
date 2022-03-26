

$(document).ready(function() {
    var mySlidebars = new $.slidebars();

    $('.toggle-left').on('click', function() {
        mySlidebars.toggle('right');
    });
});


function change_thn(target) {
	var thn 	= $("#"+target).val();
	var curent 	= thn!=""?thn:(new Date()).getFullYear();
	var tahun 	= Number(curent);
	var html 	= "<option value=''>: : Pilih Tahun : :</option>";
	var selected= "";
	for (i=tahun-3;i<=tahun+3;i++) {
		if(thn!="") { selected = curent==i?"selected":""; }
		html += "<option "+selected+">"+i+"</option>";
	}
	$("#"+target).html(html);
}

function datepicker() {
	$(".datepicker").datepicker({ format: 'yyyy-mm-dd', autoclose: true, todayHighlight: true, });
}
function datepicker_custom() {
	var today = new Date();
	var batas = new Date(today.getTime()+(31*24*60*60*1000));
	$(".datepicker").datepicker({ 
		format: 'yyyy-mm-dd', 
		autoclose: true,
		startDate: today,
		endDate: batas,
		todayHighlight: true, 
	});
}

function out_load() {
	$('.preload-wrapper').hide();
}
function in_load() {
	$('.preload-wrapper').show();
}

function error_detail(error) {
	console.log(error);
	if(error.responseJSON.status=="warning") {
		swal('Warning',''+error.responseJSON.messages+'','warning');
		return false;

	} else if(error.responseJSON.status=="error") {
		swal('Error',''+error.responseJSON.messages+'','error');
		return false;
	} else {
		swal(''+error.status+'',''+error.responseJSON.message+'','error');
		return false;
	}
}

$.extend( $.fn.dataTable.defaults, {
	processing: true,
	autoWidth: false,
	responsive: true,
	columnDefs: [{ 
		orderable: false,
	}],
	// dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
	language: {
		search: '_INPUT_',
		searchPlaceholder: 'Cari Data',
		lengthMenu: '_MENU_',
		paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
	},
});



function angka(objek) {
	a = objek.value;
	b = a.replace(/[^\d]/g,"");
	objek.value = b;
}

function rupiah(objek) {
	separator = ".";
	a = objek.value;
	b = a.replace(/[^\d]/g,"");
	c = "";
	panjang = b.length; 
	j = 0; 
	for (i = panjang; i > 0; i--) {
			j = j + 1;
			if (((j % 3) == 1) && (j != 1)) {
				c = b.substr(i-1,1) + separator + c;
		} else {
				c = b.substr(i-1,1) + c;
		}
	}
	objek.value = c;
}


function conver_rupiah(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + '.' + '$2');
    }
    return x1 + x2;
}
function conver_angka(nStr) {
	var stringValue = nStr.toString();
	return stringValue.replace(/[^\d]/g,"");
}

function removeItemArray(arr, item){
	return arr.filter(f => f !== item)
}