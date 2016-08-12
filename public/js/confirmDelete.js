function confirmDelete() {
    if (confirm("Are you shure?")) {
        return true;
    } else {
        return false;
    }
}

window.onload = function() {
    var confDel = document.getElementsByClassName('confirmDel');
    for (var i = 0; i < confDel.length; i++) {
        confDel[i].onclick = confirmDelete;
    }
}
