// prettier-ignore

function getCookie(name) {
	var matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));
	return matches ? decodeURIComponent(matches[1]) : undefined;
}

function toogleTheme() {
    let current = getCookie("theme");

    newValue = current == undefined || current == "light" ? "dark" : "light";

    document.cookie = `theme=${newValue};path=/;max-age=31556926`;

    window.location.reload();
}

function toolgeLoading() {
    console.log("toolgeLoading")

    setTimeout(function() {
        $('select[data-id="analytics-months"]').attr("disabled", true);
        $('select[data-id="analytics-users"]').attr("disabled", true);
        $('input[data-id="analytics-load"]').attr("disabled", true);
        $('button[data-id="analytics-sync"]').attr("disabled", true);
        $('button[data-id="analytics-company"]').attr("disabled", true);
        $('button[data-id="analytics-back"]').attr("disabled", true);

        $('[data-id="loading"] > div').show();
        $('[data-id="content"]').hide();

        console.log("setTimeout")
    }, 100);
}

$('button[data-id="analytics-sync"], input[data-id="analytics-load"], button[data-id="analytics-company"]')
    .on("click", function () {
        console.log("in")
        toolgeLoading();
    });

const loadFileEvent = function(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('image');
        output.src = reader.result;
        const preview = document.getElementById('preview');
        if (preview) {
            preview.src = reader.result;
        }
    };
    reader.readAsDataURL(event.target.files[0]);
};

function UpdateTeams() {
    const url = $(this).attr('action');
    const formData = new FormData(this);
    const errors = $("#auth-validation-errors");

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: url,
        type: "POST",
        enctype: 'multipart/form-data',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        success: function (response) {
            console.log(response)

            if (response.status === 400) {
                const ul = $(errors).find("ul").empty()
                $.each(response.errors, function (key, item) {
                    $(ul).append(`<li>${item[0]}</li>`)
                });
                $(errors).show();
            } else {
                $(errors).hide();
                if(response.redirect_url){
                    window.location=response.redirect_url;
                }
            }
        }
    });
}

function CreateTeams() {
    const url = $(this).attr('action');
    const formData = new FormData(this);
    const errors = $("#auth-validation-errors");

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    console.log([...formData])

    $.ajax({
        url: url,
        type: "POST",
        enctype: 'multipart/form-data',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        success: function (response) {
            console.log(response)

            if (response.status === 400) {
                const ul = $(errors).find("ul").empty()
                $.each(response.errors, function (key, item) {
                    $(ul).append(`<li>${item[0]}</li>`)
                });
                $(errors).show();
            } else {
                $(errors).hide();
                if(response.redirect_url){
                    window.location=response.redirect_url;
                }
            }
        }
    });
}
