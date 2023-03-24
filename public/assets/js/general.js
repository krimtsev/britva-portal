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
