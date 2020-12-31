function SweetAlert(msg, icon) {
    Swal.fire({
        title: '',
        text: msg,
        icon: icon,
        confirmButtonText: 'ตกลง',
    });
}

function SweetAlertOk(msg, icon, targetPage) {
    Swal.queue([
        {
            title: '',
            icon: icon,
            confirmButtonText: 'ตกลง',
            text: msg,
            showLoaderOnConfirm: true,
            preConfirm: () => {
                window.location.href = targetPage;
            },
        },
    ]);
}

function SweetAlertConfirm(msg, icon, targetPage) {
    Swal.queue([
        {
            title: '',
            icon: icon,
            confirmButtonText: 'ตกลง',
            cancelButtonText: 'ยกเลิก',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#ccc',
            text: msg,
            showLoaderOnConfirm: true,
            showCancelButton: true,
            preConfirm: () => {
                window.location.href = targetPage;
            },
        },
    ]);
}
