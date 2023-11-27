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
    setTimeout(function() {
        $('select[data-id="analytics-months"]').attr("disabled", true);
        $('select[data-id="analytics-users"]').attr("disabled", true);
        $('input[data-id="analytics-load"]').attr("disabled", true);
        $('button[data-id="analytics-sync"]').attr("disabled", true);
        $('button[data-id="analytics-company"]').attr("disabled", true);

        $('[data-id="loading"] > div').show();
        $('[data-id="content"]').hide();
    }, 100);
}
$('input[data-id="analytics-sync"], input[data-id="analytics-load"], input[data-id="analytics-company"]')
    .on("click", function() {
        toolgeLoading();
});

