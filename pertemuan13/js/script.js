$(document).ready(function() {
    $('#tombol-cari').hide();

    // event ketika ditulis
    $('#keyword').on('keyup', function() {
        // munculkan icon loading
        $('.loader').show();

        // ajax menggunakan load
//         $('#container').load('ajax/mahasiswa.php?keyword=' + $('#keyword').val());

        // ajax menggunakan $.get()
        $.get('ajax/mahasiswa.php?keyword=' + $('#keyword').val(), function(data) {
            $('#container').html(data);
            $('.loader').hide();
        });
    });
});
