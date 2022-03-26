<script>
    
function add_produk() {
    $("#modal_title").text('Detail Produk');
    $("#modal_size").addClass("modal-lg");
    $("#modal_content").html("<table class='table table-x1 table-striped table-bordered' id='data_table'>"
        +"<thead class='table_head'>"
            +"<tr class='bg-slate-800'>"
                +"<th>Kode produk</th>"
                +"<th>Nama produk</th>"
                +"<th>Satuan</th>"
                +"<th>Kategori</th>"
                +"<th>Harga</th>"
            +"</tr>"
    +"</thead>"
    +"</table>");
    
    var table_produk = $('#data_table').DataTable({
        "serverSide": true,
        "ajax": {
            "url"       : "{{ asset('produk') }}",
            "type"      : "get",
        },
        "columns": [
            { "data": "kd_produk" },
            { "data": "nm_produk" },
            { "data": "satuan.satuan", "name":"satuan.satuan" },
            { "data": "kategori.kategori", "name":"kategori.kategori" },
            { "data": "harga" }
        ],
        "order": []
    });

    $('#data_table tbody').on('click', 'tr', function () {
        var data = table_produk.row(this).data();
        load_produk(data, 1);
    });

    $("#modal_footer").hide();
    $("#modal_detail").modal('show');
}


var all_id = [];
function load_produk(data, qty) {
    var data_produk   = $("#data_produk");
    if(all_id.length<=0) {data_produk.html("");}
    if($("#produk_id"+data.id).val()) { swal('warning','data produk sudah ada','warning'); return false; }
    all_id.push(data.id);
    data_produk.append("<tr id='row_temp"+data.id+"'>"
        +"<td><button type='button' onclick='delete_temp("+data.id+")' class='btn btn-danger btn-act'><span class='entypo-trash'></span></button></td>"
        +"<td><input type='hidden' name='produk_id[]' id='produk_id"+data.id+"' value='"+data.id+"'>"+data.kd_produk+"</td>"
        +"<td>"+data.nm_produk+"</td>"
        +"<td>"+data.kategori.kategori+"</td>"
        +"<td>"+data.satuan.satuan+"</td>"
        +"<td class='td_input'><input type='text' class='form-control form-control-sm' name='qty[]' onkeyup='total("+data.id+")' value='"+qty+"' id='qty"+data.id+"'></td>"
        +"<td class='td_input'><input type='text' class='form-control form-control-sm' name='harga[]' id='harga"+data.id+"' value='"+data.harga+"' readonly='true'></td>"
        +"<td class='td_input'><input type='text' class='form-control form-control-sm' name='total_harga[]' id='total_harga"+data.id+"' value='0' readonly='true'></td>"
    +"</tr>");
    total(data.id);
    $("#modal_detail").modal('hide');
}

function delete_temp(id) {
    $("#row_temp"+id).remove();
    all_id = removeItemArray(all_id, id);
    if(all_id.length<=0) {$("#data_produk").html("<tr><td colspan='8'><div class='td_null'>Data Belum Ada</div></td></tr>");}
}


function total(produk_id) {
    var total = Number(conver_angka($("#qty"+produk_id).val()))*Number(conver_angka($("#harga"+produk_id).val()));
    $("#qty"+produk_id).val(conver_angka($("#qty"+produk_id).val()));
    $("#total_harga"+produk_id).val("Rp. "+conver_rupiah(total));
    total_bayar(all_id);
}

function total_bayar(produk_id) {
    var total = "0";
    for(i=0;i<produk_id.length;i++) { total = Number(conver_angka(total))+Number(conver_angka($("#total_harga"+produk_id[i]).val())); }
    $("#total_bayar").val("Rp. "+conver_rupiah(total));
}
</script>